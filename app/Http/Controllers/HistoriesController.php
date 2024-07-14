<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\History;
use App\Models\Scooter;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;

class HistoriesController extends Controller
{
    public function index()
    {
        $histories = History::all();
        $passengers = $latestPassengers = Passenger::select('passengers.*', 'scooters.scooter', 'scooters.status')
            ->join('scooters', 'passengers.scooter_id', '=', 'scooters.id')
            ->orderBy('created_at', 'Desc')
            ->get();
        return view('history', compact('histories', 'passengers'));
    }

    public function getHistories($id)
    {
        $histories = History::where('passenger_id', $id)->get();
        return response()->json($histories);
    }

    public function history($passenger_id)
    {
        $histories = History::where('passenger_id', $passenger_id)->get(['latitude', 'longitude']);
        $passenger = Passenger::find($passenger_id);
        $scooter = Scooter::where('id', $passenger->scooter_id)->first();
        return view('map', compact('histories', 'passenger', 'scooter'));
    }

    public function getLatestHistories($passenger_id)
    {
        $latestHistories = History::where('passenger_id', $passenger_id)->latest()->get(['latitude', 'longitude']);
        return response()->json($latestHistories);
    }

    public function antares()
    {
        $scooters = Scooter::where('status', false)->get();
        $responses = [];
        $client = new Client();
        $currentTime = now();

        foreach ($scooters as $scooter) {
            $url = 'https://platform.antares.id:8443/~/antares-cse/antares-id/ScooterTracker/' . $scooter->scooter . '/la';

            try {
                $response = $client->get($url, [
                    'headers' => [
                        'X-M2M-Origin' => '55b8f07603b3cb51:af42906767b7f8e1',
                        'Content-Type' => 'application/json;ty=4',
                        'Accept' => 'application/json'
                    ],
                ]);
                $responseData = json_decode($response->getBody()->getContents(), true);

                if (isset($responseData['m2m:cin']) && isset($responseData['m2m:cin']['con'])) {
                    $con = $responseData['m2m:cin']['con'];
                    $conData = json_decode($con, true);
                    $lt = $responseData['m2m:cin']['lt'];

                    if (isset($conData['type']) && $conData['type'] == 'uplink' && isset($conData['data'])) {
                        $dataParts = explode(',', $conData['data']);
                        $lat = $dataParts[0] ?? null;
                        $long = $dataParts[1] ?? null;
                        $time = $lt;

                        if (!empty($lat) && !empty($long)) {
                            // Cari penumpang (passenger) yang sedang dalam perjalanan
                            $passenger = Passenger::where('scooter_id', $scooter->id)
                                ->where('start', '<=', $currentTime)
                                ->where('end', '>=', $currentTime)
                                ->first();

                            if ($passenger) {
                                // Cek apakah ada data dengan waktu yang sama
                                $existingHistory = History::where('passenger_id', $passenger->id)
                                    ->where('time', $time)
                                    ->first();

                                if (!$existingHistory) {
                                    // Simpan data ke dalam tabel histories
                                    History::create([
                                        'passenger_id' => $passenger->id,
                                        'latitude' => $lat,
                                        'longitude' => $long,
                                        'time' => $time,
                                    ]);

                                    $responses[] = [
                                        'type' => 'uplink',
                                        'id' => $scooter->id,
                                        'scooter' => $scooter->scooter,
                                        'lat' => $lat,
                                        'long' => $long,
                                        'time' => $time,
                                        'passenger_id' => $passenger->id
                                    ];
                                }
                            }
                        }
                    }
                }
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $statusCode = $e->getResponse()->getStatusCode();
                    $errorMessage = $e->getResponse()->getReasonPhrase();
                    $errorResponse = [
                        'device' => $scooter->scooter,
                        'error' => 'Request failed with status code ' . $statusCode,
                        'message' => $errorMessage
                    ];
                    $responses[] = $errorResponse;
                } else {
                    $responses[] = [
                        'error' => 'Request failed without a response',
                        'message' => $e->getMessage()
                    ];
                }
            }
        }
        dd($responses);
    }
}
