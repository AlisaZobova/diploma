<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmbeddingRequests\CreateRequest;
use App\Services;

class OpenAIController extends Controller
{
    public function __construct(
        private Services\OpenAIService $openAIService
    ) {
        //
    }

    public function createEmbedding(CreateRequest $request)
    {
        $content = $request->input('content');

        return $this->openAIService->createEmbedding($content);
    }

    public function seedEmbeddings(CreateRequest $request)
    {
        return $this->openAIService->seedEmbeddings();
    }

    public function saveEmbeddingsToCsv()
    {
        return $this->openAIService->saveEmbeddingsToCsv();
    }

    public function saveScoresToCsv()
    {
        return $this->openAIService->saveScoresToCsv();
    }
}
