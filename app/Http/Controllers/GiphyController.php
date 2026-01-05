<?php

namespace App\Http\Controllers;

use App\Services\GiphyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GiphyController extends Controller
{
    public function __construct(
        protected GiphyService $giphyService
    ) {}

    public function trending(Request $request): JsonResponse
    {
        $limit = $request->integer('limit', 20);
        $offset = $request->integer('offset', 0);

        $result = $this->giphyService->trending($limit, $offset);

        return response()->json($result);
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->string('q')->toString();
        $limit = $request->integer('limit', 20);
        $offset = $request->integer('offset', 0);

        $result = $this->giphyService->search($query, $limit, $offset);

        return response()->json($result);
    }
}
