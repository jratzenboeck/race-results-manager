<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class PentekTimingGateway
{
    /**
     * @throws RequestException
     */
    public function projects(string $projectName, Carbon $from, Carbon $to): array
    {
        $response = $this->get('Projects', 'name=' . $projectName .
            '&from_date=' . $from->format('Y-m-d') . '&until_date=' . $to->format('Y-m-d'));

        return $response->json('Projects');
    }

    /**
     * @throws RequestException
     */
    public function competitions(int $projectNumber): array
    {
        $response = $this->get('Competitions', 'pnr=' . $projectNumber);

        return $response->json('Competitions');
    }

    /**
     * @throws RequestException
     */
    private function get(string $resourceType, string $queryString)
    {
        $response = Http::get(
            config('services.pentek_timing.url') . '/get ' . $resourceType . '?' . $queryString
        );
        return $response->throwIf($response->failed());
    }
}
