<?php

namespace App\Services;

use App\Models\Gender;
use App\Models\Race;
use App\Models\RaceResult;
use App\Models\RaceSplit;
use App\Models\User;

class PentekTimingProvider implements RaceResultsProvider
{
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

    private function getProject(Race $race): array
    {
        return [];
    }

    private function getCompetition(int $pnr, Race $race): array
    {
        return [];
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
