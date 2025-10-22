<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TrackCircuit;
use Illuminate\Support\Facades\Http;

class FetchTrackCircuits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wmata:fetch-track-circuits';
    // run this command with php artisan wmata:fetch-track-circuits

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch track circuits from WMATA API and store them locally';

    /**
     * Execute the console command.
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
            ->get('https://api.wmata.com/TrainPositions/TrackCircuits?contentType=json');

        if ($response->failed()) {
            $this->error('Failed to fetch track circuits from WMATA API.');
            return 1;
        }

        $trackCircuits = $response->json('TrackCircuits', []);

        foreach ($trackCircuits as $circuit) {
            TrackCircuit::updateOrCreate(
                ['circuit_id' => $circuit['CircuitId']],
                [
                    'track' => $circuit['Track'],
                    'neighbors' => json_encode($circuit['Neighbors']),
                ]
            );
        }

        $this->info('Track circuits successfully updated.');
        return 0;
    }
}
