<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class MapLocationController extends Controller
{
    public function getRestaurantsByLocation(Request $request)
    {
        // Get google map APIs key from .env file
        $apiKey = env('GOOGLE_MAP_KEY');
        $isRedisSetup = true;
        $dataResult = null;

        try {
            // In case server setup redis to caching the service will checking cache location before call google APIs,
            // If cannot find cache on redis the service will call google APIs directly,
            // but if can find cache on redis the service will use data location with cache
            if (Redis::exists($request->location . '_' . $request->pageid)) {
                $dataResult = json_decode(Redis::get($request->location . '_' . $request->pageid));
            }
        } catch (\Throwable $th) {
            // In case not setup redis on server 
            $isRedisSetup = false;
        } finally {
            if (!isset($dataResult)) {
                // In case havn't data in redis or No setup cache redis the service will get data from google APIs
                $queryGetPlace = [
                    'query' => $request->location,
                    'type' => 'restaurant',
                    'radius' => 150000,
                    'key' => $apiKey
                ];

                // Add page token if need to query more place for default number of query rows the google APIs set as 20 rows per query 
                if (isset($request->pageid)) {
                    $queryGetPlace['pagetoken'] = $request->pageid;
                }

                $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json', $queryGetPlace);
                $responseStatus = $response->status();
                $dataResult = $response->json();

                // If server setup redis to caching the service will caching location data to redis before return result,
                // For use cache data in next time if user search for location data with same location
                if ($isRedisSetup && $responseStatus === 200 && isset($dataResult)) {
                    // Set cache redis expire within 1 hr (3600 sec)
                    Redis::set($request->location . '_' . $request->pageid, json_encode($dataResult), 'EX', 3600);
                }
            }

            // Return data to APIs service
            return [
                "statusCode" => 200,
                "message" => "Successfully",
                "data" => $dataResult
            ];
        }
    }
}
