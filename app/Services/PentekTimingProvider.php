<?php

namespace App\Services;

use App\Models\Gender;
use App\Models\Race;
use App\Models\RaceResult;
use App\Models\RaceSplit;
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
    public function fetchResultFor(Race $race, User $user): array
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
        $splitTimes = $this->getDetailResult($project['pnr'], $competition['cnr'], $resultOfUser['bibNumber']);

        $raceResult = RaceResult::fromProvider($resultData, 'pentek');
        $raceSplits = RaceSplit::fromProvider($splitTimes, 'pentek');

        return compact('raceResult', 'raceSplits');
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
    private function getCompetition(int $pnr, Race $race): array
    {
        $competitions = PentekTimingGateway::competitions($pnr);

        return Arr::first(
            $competitions,
            fn ($competition) => Str::contains($competition['name'], $race->name),
            fn () => throw new Exception('Pentek timing competition could not be found for race name ' . $race->name)
        );
    }

    private function getResult(int $pnr, int $cnr, User $user): array
    {
        return [];
    }

    private function getDetailResult(int $pnr, int $cnr, int $bibNumber): array
    {
        return [];
    }
}
