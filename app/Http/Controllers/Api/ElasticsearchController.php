<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ElasticSearchRequests\GetRecommendationsRequest;
use App\Services\ElasticsearchService;

class ElasticsearchController extends Controller
{
    public function __construct(
        private ElasticsearchService $elasticsearchService
    ) {
        //
    }
    public function getRecommendations(GetRecommendationsRequest $request) {
        $searchRequest = $request->query('search');
        $resultsCount = $request->query('count') ?? 10;
        return $this->elasticsearchService->getRecommendations($searchRequest, $resultsCount);
    }
}
