<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GiphyService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $limit;

    public function __construct()
    {
        $this->apiKey = config('giphy.api_key');
        $this->baseUrl = config('giphy.base_url');
        $this->limit = config('giphy.limit');
    }

    public function trending(int $limit = null, int $offset = 0): array
    {
        $response = Http::get("{$this->baseUrl}/trending", [
            'api_key' => $this->apiKey,
            'limit' => $limit ?? $this->limit,
            'offset' => $offset,
            'rating' => 'pg-13',
        ]);

        if ($response->failed()) {
            return ['data' => [], 'pagination' => []];
        }

        return $this->formatResponse($response->json());
    }

    public function search(string $query, int $limit = null, int $offset = 0): array
    {
        if (empty(trim($query))) {
            return $this->trending($limit, $offset);
        }

        $response = Http::get("{$this->baseUrl}/search", [
            'api_key' => $this->apiKey,
            'q' => $query,
            'limit' => $limit ?? $this->limit,
            'offset' => $offset,
            'rating' => 'pg-13',
        ]);

        if ($response->failed()) {
            return ['data' => [], 'pagination' => []];
        }

        return $this->formatResponse($response->json());
    }

    protected function formatResponse(array $response): array
    {
        $gifs = collect($response['data'] ?? [])->map(function ($gif) {
            return [
                'id' => $gif['id'],
                'title' => $gif['title'] ?? '',
                'url' => $gif['images']['fixed_height']['url'] ?? '',
                'preview_url' => $gif['images']['fixed_height_still']['url'] ?? '',
                'original_url' => $gif['images']['original']['url'] ?? '',
                'width' => $gif['images']['fixed_height']['width'] ?? 0,
                'height' => $gif['images']['fixed_height']['height'] ?? 0,
            ];
        })->toArray();

        return [
            'data' => $gifs,
            'pagination' => $response['pagination'] ?? [],
        ];
    }
}
