<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait CallRelationship
{
    protected function belongsToAnother($base_url, $related, $foreignData, $cacheKey = 'external', $ttl = 300)
    {
        if (env('USE_CACHE_WHEN_CALL_EXTERNAL_API', false)) {
            // Caching, make sure application not always fetching the data from external API (This causing performance issues)
            if (Cache::has($cacheKey)) {
                $data = Cache::get($cacheKey, $this->getRepository($base_url, $related, $foreignData));
            } else {
                Cache::forget($cacheKey);
                $data = Cache::remember($cacheKey, $ttl, $this->getRepository($base_url, $related, $foreignData));
            }
        } else {
            // No cache
            $data = json_decode(Http::get("{$base_url}/{$related}/{$foreignData}")->body());
        }

        return $data;
    }

    private function getRepository($base_url, $related, $foreignData)
    {
        $url = "{$base_url}/{$related}/{$foreignData}";

        return function () use ($url) {
            $get = Http::get($url);

            if ($get->clientError()) {
                Log::channel('slack')->error('Call error', ['Response' => json_decode($get->body())]);
            }

            return json_decode($get->body());
        };
    }
}
