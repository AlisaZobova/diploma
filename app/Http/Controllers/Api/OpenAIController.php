<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services;
use App\Http\Requests\EmbeddingRequests\CreateRequest;

class OpenAIController extends Controller
{
    public function __construct(
        private Services\OpenAIService $openAIService
    ) {
        //
    }
    public function createEmbedding(CreateRequest $request) {
        $content = $request->input('content');
        return $this->openAIService->createEmbedding($content);
    }
}
