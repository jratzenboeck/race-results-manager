<?php

namespace App\Services;

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
        $resultData = [
            'participants_total' => $competition['competitor_count_total'],
            'participants_gender' => $this->numberOfParticipantsForGender($competition, $user->gender)
        ];
        $resultOfUser = $this->getResult($project['pnr'], $competition['cnr'], $user);
        $resultData = array_merge($resultData, [
            'age_group' => $resultOfUser['category'],
            'rank_total' => $resultOfUser['rank'],
            'rank_gender' => $resultOfUser['genderrank'],
            'rank_age_group' => $resultOfUser['catrank'],
            'total_time' => $resultOfUser['time']
        ]);
        $detailResult = $this->getDetailResult($project['pnr'], $competition['cnr'], $resultOfUser['bibNumber']);
        $splitResults = $this->computeSplitsFrom($detailResult);

        $raceResult = RaceResult::from($resultData);
        $raceSplits = RaceSplit::from($splitResults, $raceResult);

        return new RaceResultDetails($raceResult, $raceSplits);
    }

    private function numberOfParticipantsForGender(array $competition, Gender $gender)
    {
        return match ($gender) {
            Gender::MALE => $competition['competitior_count_male'],
            Gender::FEMALE => $competition['competitior_count_female'],
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
            fn ($competition) => Str::contains($competition['name'], $race->name),
            fn () => throw new Exception('Pentek timing competition could not be found for race name ' . $race->name)
        );
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
            $athlete = $this->getAthlete($results, $user);

            $from = $to + 1;
            $to += $count;
        } while ($athlete == null && count($results) == 50);

        if ($athlete == null) {
            throw new Exception('Pentek timing athlete ' . $user->name .
                ' could not be found for project number ' . $projectNumber .
                ' and competition number ' . $competitionNumber);
        }
        return $athlete;
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

    private function getAthlete(array $results, User $user): ?array
    {
        $participants = collect($results)->map(fn ($result) => Arr::first($result['Participants']));

        return $participants?->first(
            fn ($participant) => $participant['firstname'] == $user->first_name &&
                $participant['lastname'] == $user->last_name
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
                    compact('type', 'distanceUnit', 'distance', 'time'),
                    [
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

    private function distance(int $distance, string $distanceUnit): float
    {
        return match ($distanceUnit) {
            'Meter' => round((float)$distance / 100, 2),
            'Kilometer' => round((float)$distance / 1000 * 100, 2)
        };
    }

    private function time(int $timeValue): string
    {
        return date('H:i:s', $timeValue / 1000);
    }
}
