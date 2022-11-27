<?php

namespace App\Services;

use App\Facades\PentekTimingGateway;
use App\Helpers\RaceResultDetails;
use App\Models\Gender;
use App\Models\Race;
use App\Models\RaceResult;
use App\Models\RaceSplit;
use App\Models\RaceSplitType;
use App\Models\User;
use Arr;
use Exception;
use Illuminate\Http\Client\RequestException;
use Str;

class PentekTimingProvider implements RaceResultsProvider
{
    /**
     * @throws RequestException
     */
    public function fetchResultFor(Race $race, User $user): RaceResultDetails
    {
        $project = $this->getProject($race);
        $competition = $this->getCompetition($project['pnr'], $race);
        $competitionDetails = $this->getCompetitionDetails($project['pnr'], $competition['cnr']);
        $resultData = [
            'participants_total' => $competitionDetails['competitor_count_total'],
            'participants_gender' => $this->numberOfParticipantsForGender($competitionDetails, $user->gender)
        ];
        $resultOfUser = $this->getResult($project['pnr'], $competitionDetails['cnr'], $user);
        $resultData = array_merge($resultData, [
            'age_group' => $resultOfUser['category'],
            'rank_total' => $resultOfUser['rank'],
            'rank_gender' => $resultOfUser['genderrank'],
            'rank_age_group' => $resultOfUser['catrank'],
            'total_time' => $resultOfUser['time']
        ]);
        $detailResult = $this->getDetailResult($project['pnr'], $competitionDetails['cnr'], $resultOfUser['bib']);
        $splitResults = $this->computeSplitsFrom($detailResult);

        $raceResult = RaceResult::from($resultData, $user, $race->raceable);
        $raceSplits = RaceSplit::from($splitResults);

        return new RaceResultDetails($raceResult, $raceSplits);
    }

    private function numberOfParticipantsForGender(array $competition, Gender $gender)
    {
        return match ($gender) {
            Gender::MALE => $competition['competitor_count_male'],
            Gender::FEMALE => $competition['competitor_count_female'],
            Gender::DIVERS => null
        };
    }

    /**
     * @throws RequestException
     * @throws Exception
     */
    private function getProject(Race $race): array
    {
        $projects = PentekTimingGateway::projects($race->name, $race->date->startOfMonth(), $race->date->endOfMonth());

        if (!empty($projects)) {
            return $projects[0];
        } else {
            throw new Exception('Pentek timing project could not be found for race name ' . $race->name);
        }
    }

    /**
     * @throws RequestException
     */
    private function getCompetition(int $projectNumber, Race $race): array
    {
        $competitions = PentekTimingGateway::competitions($projectNumber);

        return Arr::first(
            $competitions,
            fn ($competition) => $this->matchesCompetition($race, $competition),
            fn () => throw new Exception('Pentek timing competition could not be found for race name ' . $race->name)
        );
    }

    private function getCompetitionDetails(int $projectNumber, int $competitionNumber)
    {
        return PentekTimingGateway::competition($projectNumber, $competitionNumber);
    }

    /**
     * @throws Exception
     */
    private function getResult(int $projectNumber, int $competitionNumber, User $user): array
    {
        $from = 0;
        $count = 50;
        $to = $count;

        do {
            $results = PentekTimingGateway::results($projectNumber, $competitionNumber, $from, $to);
            $resultsOfAthlete = $this->getResultsOfAthlete($results, $user);

            $from = $to;
            $to += $count;
        } while ($resultsOfAthlete == null && count($results) == 50);

        if ($resultsOfAthlete == null) {
            throw new Exception('Pentek timing athlete ' . $user->name .
                ' could not be found for project number ' . $projectNumber .
                ' and competition number ' . $competitionNumber);
        }
        return $resultsOfAthlete;
    }

    private function getDetailResult(int $projectNumber, int $competitionNumber, int $bibNumber): array
    {
        $detailResults = PentekTimingGateway::detailResults($projectNumber, $competitionNumber, $bibNumber);
        $detailResult = Arr::first(
            $detailResults,
            null,
            fn () => throw new Exception('Pentek timing detail results empty')
        );

        return $detailResult['DetailResultRows'];
    }

    private function getResultsOfAthlete(array $results, User $user): ?array
    {
//        $participants = collect($results)->map(fn ($result) => Arr::first($result['Participants']));

        return collect($results)?->first(
            fn ($result) => $result['Participants'][0]['firstname'] == $user->firstName() &&
                $result['Participants'][0]['lastname'] == $user->lastName()
        );
    }

    private function computeSplitsFrom(array $detailResultRows): array
    {
        return array_filter(array_map(function ($resultRow) {
            $type = $this->splitType($resultRow['description']);
            if ($type != null) {
                $distanceUnit = $this->distanceUnitBasedOnType($type);
                $distance = $this->distance($resultRow['distance'], $distanceUnit);
                $time = $this->time($resultRow['timevalue']);

                return array_merge(
                    compact('type', 'distance', 'time'),
                    [
                        'distance_unit' => $distanceUnit,
                        'rank_total' => $resultRow['rank'],
                        'rank_gender' => $resultRow['genderrank'],
                        'rank_age_group' => $resultRow['catrank']
                    ]
                );
            }
            return null;
        }, $detailResultRows));
    }

    private function splitType(string $description): ?RaceSplitType
    {
        if (Str::contains($description, 'Swim')) {
            return RaceSplitType::SWIM;
        }
        return match ($description) {
            'Trans1' => RaceSplitType::TRANSITION1,
            'BikeFinish' => RaceSplitType::BIKE,
            'Trans2' => RaceSplitType::TRANSITION2,
            'Finish' => RaceSplitType::RUN,
            default => null
        };
    }

    private function distanceUnitBasedOnType(RaceSplitType $type): ?string
    {
        return match ($type) {
            RaceSplitType::SWIM => 'Meter',
            RaceSplitType::BIKE, RaceSplitType::RUN => 'Kilometer',
            default => null
        };
    }

    private function distance(int $distance, ?string $distanceUnit): ?float
    {
        return match ($distanceUnit) {
            'Meter' => round((float)$distance / 100, 2),
            'Kilometer' => round((float)$distance / 1000 * 100, 2),
            default => null
        };
    }

    private function time(int $timeValue): string
    {
        return date('H:i:s', $timeValue / 1000);
    }

    private function matchesCompetition(Race $race, array $competition): bool
    {
        $totalDistanceOfRace = $race->raceable->totalDistanceInMeters();
        $totalDistanceOfCompetition = $competition['distance'] / 100;

        return $this->matchesName($race->name, $competition['name']) ||
            $this->isWithinOffset($totalDistanceOfRace, $totalDistanceOfCompetition);
    }

    private function isWithinOffset($totalDistanceOfRace, float $totalDistanceOfCompetition, int $maxOffset = 2000): int
    {
        return abs($totalDistanceOfCompetition - $totalDistanceOfRace) < $maxOffset;
    }

    private function matchesName(string $raceName, string $competitionName): bool
    {
        return Str::contains($competitionName, $raceName, true);
    }
}
