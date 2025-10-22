<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Station;

class FetchStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch stations from WMATA API and store in database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Force load .env
        $dotenv = \Dotenv\Dotenv::createImmutable(base_path());
        $dotenv->load();

        $apiKey = env('WMATA_API_KEY');

        if (!$apiKey) {
            $this->error('WMATA_API_KEY is not set in .env');
            return 1;
        }

        $response = Http::withHeaders(['api_key' => $apiKey])
            ->get('https://api.wmata.com/Rail.svc/json/jStations');

        if (!$response->successful()) {
            $this->error('Failed to fetch stations from WMATA API');
            return 1;
        }

        $stations = $response->json()['Stations'] ?? [];

        foreach ($stations as $station) {
            Station::updateOrCreate(
                ['station_code' => $station['Code']],
                [
                    'name' => $station['Name'],
                    'lat' => $station['Lat'],
                    'lng' => $station['Lon'],
                    'lines' => json_encode(array_filter([
                        $station['LineCode1'],
                        $station['LineCode2'],
                        $station['LineCode3'],
                        $station['LineCode4'],
                    ])),
                ]
            );
        }

        $this->info('Stations successfully fetched and saved.');
        return 0;
    }
}
