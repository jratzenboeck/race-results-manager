<?php

use App\Facades\PentekTimingGateway;
use Illuminate\Support\Carbon;

it('should get projects within given date range', function () {
    $projects = PentekTimingGateway::projects(
        'Linz Triathlon',
        Carbon::parse('2022-05-01'),
        Carbon::parse('2022-05-31')
    );

    $this->assertCount(1, $projects);
    $this->assertEquals([
        'pnr' => 14216,
        'name' => '16. FH OÖ Linz Triathlon und Aqua Bike Bewerb',
        'beginDate' => '2022-05-28'
    ], Arr::only($projects[0], ['pnr', 'name', 'beginDate']));
});

it('should return an empty array if no projects could be found within given date range', function () {
    $projects = PentekTimingGateway::projects(
        'Linz Marathon',
        Carbon::parse('2022-05-01'),
        Carbon::parse('2022-05-31')
    );

    $this->assertEmpty($projects);
});

it('should return a competition for a given project', function () {
    $competitions = PentekTimingGateway::competitions(14216);

    $this->assertCount(18, $competitions);
    $this->assertEquals([
        'cnr' => 1,
        'name' => 'HalbIron-Triathlon'
    ], Arr::only($competitions[0], ['cnr', 'name']));
});

it('should return an empty array if no competitions could be found for given project number', function () {
    $competitions = PentekTimingGateway::competitions(19999);

    $this->assertEmpty($competitions);
});

it('should return details of competition for given project and competition number', function () {
    $competition = PentekTimingGateway::competition(14216, 1);

    $this->assertEquals([
        'competitor_count_total' => 202,
        'competitor_count_male' => 170,
        'competitor_count_female' => 32
    ], Arr::only($competition, ['competitor_count_total', 'competitor_count_male', 'competitor_count_female']));
});

it('throws exception if competition does not exist for given project and competition number')
    ->tap(fn () => PentekTimingGateway::competition(14216, 199))
    ->throws(
        Exception::class,
        'Competition with number 199 does not exist for project 14216'
    );

it('should return results for given project and competition', function () {
    $results = PentekTimingGateway::results(14216, 1);

    $this->assertCount(50, $results);
    $this->assertEquals([
        'bib' => 359,
        'category' => 'M-30-39',
        'genderrank' => 1,
        'rank' => 1,
        'catrank' => 1
    ], Arr::only($results[0], ['bib', 'category', 'genderrank', 'rank', 'catrank']));
});

it('should return an empty array if no results could be found for given competition', function () {
    $results = PentekTimingGateway::results(14216, 19999);

    $this->assertEmpty($results);
});

it('should return detail results for given project, competition and bib number', function () {
    $detailResults = PentekTimingGateway::detailResults(14216, 1, 359);

    $this->assertCount(1, $detailResults);
    $this->assertEquals([
        'bib' => 359,
        'name' => 'Müllner Peter',
        'category' => 'M-30-39',
        'rankFinal' => 1,
        'genderRankFinal' => 1,
        'catRankFinal' => 1,
        'gender' => 'Male',
        'special' => 'regular'
    ], Arr::only($detailResults[0], [
        'bib', 'name', 'category', 'rankFinal', 'genderRankFinal', 'catRankFinal', 'gender', 'special'
    ]));
    $this->assertCount(8, $detailResults[0]['DetailResultRows']);
});

it(
    'should return an empty array if no detail results could be found for project, competition and bib number',
    function () {
        $results = PentekTimingGateway::results(14216, 1, 1999);

        $this->assertEmpty($results);
    }
);
