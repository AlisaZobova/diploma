<?php

namespace App\Services;

use App\Facades\ElasticsearchFacade;
use App\Facades\OpenAI\Enums\Model;
use App\Facades\OpenAIFacade;

class OpenAIService {
    public const MODEL = Model::ADA_002;

    public function createEmbedding(string $content) {
        $embeddingContent = OpenAIFacade::createEmbedding(self::MODEL, $content)->getEmbedding();
        ElasticsearchFacade::createDocument($embeddingContent, $content);
        return $embeddingContent;
    }
}
