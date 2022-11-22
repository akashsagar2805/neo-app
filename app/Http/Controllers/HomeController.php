<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $allResponse = [];

        if ($request->has('start_date') && $request->has('end_date')) {

            $request->validate([
                "start_date" => "required|date",
                "end_date" => [
                    "required",
                    "date",
                    "after:start_date",
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->start_date) {
                            $startDate = Carbon::parse($request->start_date);
                            $endDate = Carbon::parse($value);

                            if ($startDate->diffInDays($endDate) > 6) {
                                $fail('The end data should not be more then a week with start date');
                            }
                        }
                    }
                ]
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $response = Http::get(config('neo-api.url')
                . 'start_date=' . $startDate
                . '&end_date=' . $endDate
                . '&api_key=' . config('neo-api.api_key'))->json();

            $allAsteroids = [];
            $totalSize = 0;
            $chartData = [];

            foreach ($response['near_earth_objects'] as $date => $asteroids) {
                array_push($chartData, [
                    "label" => $date,
                    "count" => count($asteroids)
                ]);

                foreach ($asteroids as $asteroid) {
                    array_push($allAsteroids, [
                        "id" => $asteroid['id'],
                        "speed" => $asteroid['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'],
                        "distance" => $asteroid['close_approach_data'][0]['miss_distance']['kilometers']
                    ]);

                    $totalSize += (float)$asteroid['estimated_diameter']['kilometers']['estimated_diameter_min'] * (float)$asteroid['estimated_diameter']['kilometers']['estimated_diameter_max'];
                }
            }

            $fastestAsteroids = array_reverse(Arr::sort($allAsteroids, function ($asteroid) {
                return $asteroid['speed'];
            }));

            $closestAsteroids = array_values(Arr::sort($allAsteroids, function ($asteroid) {
                return $asteroid['distance'];
            }));

            $allResponse = [
                'closest' => $closestAsteroids[0],
                'fastest' => $fastestAsteroids[0],
                'avgsize' => $totalSize / count($allAsteroids),
                'chartData' => [
                    'lables' => Arr::pluck($chartData, 'label'),
                    'values' => Arr::pluck($chartData, 'count')
                ]
            ];
        }

        dump($fastestAsteroids);
        return view('welcome', compact('allResponse'));
    }
}
