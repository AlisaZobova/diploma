<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ElasticsearchService;
use Illuminate\Http\Request;

class ElasticsearchController extends Controller
{
    public function __construct(
        private ElasticsearchService $elasticsearchService
    ) {
        //
    }
    public function getRecommendations(Request $request) {
        $content = $request->query('search');
        return $this->elasticsearchService->getRecommendations($content);
    }
}
