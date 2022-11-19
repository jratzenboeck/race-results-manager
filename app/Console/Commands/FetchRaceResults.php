<?php

namespace App\Console\Commands;

use App\Models\Race;
use App\Models\User;
use App\Services\RaceResultsProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class FetchRaceResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:race-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches results of races which happened in the past.';

    private const RACE_RESULTS_PROVIDERS = ['pentek'];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*Find all races where there is no result yet and which happened in the past (at least one day before today)
        For each race
            For each race results providers
                Ask provider to load result and splits for name of race and name of user
                if result could be found
                    Extract result from response
                    Save result for race
                    Extract splits and save each split
                    break
        */
        $races = Race::whereNotExists(fn ($query) => $query->select(DB::raw(1))->from('race_results')->whereColumn('race_results.raceable_id', 'races.id'))->where('date', '<', now()->startOfDay())->get();

        foreach ($races as $race) {
            foreach (self::RACE_RESULTS_PROVIDERS as $provider) {
                try {
                    $concreteProvider = resolve(RaceResultsProvider::class, compact('provider'));
                    $raceResultDetails = $concreteProvider->fetchResultFor($race, User::findOrFail($race->author_id));
                    $raceResultDetails->raceResult->save();
                    collect($raceResultDetails->raceSplits)->each(function ($raceSplit) {
                        $raceSplit->save();
                    });
                } catch (Throwable $throwable) {
                    // Try with next provider
                }
            }
        }
        return Command::SUCCESS;
    }
}
