<?php

namespace App\Services;

use App\Facades\ElasticsearchFacade;
use App\Facades\OpenAI\Enums\Model;
use App\Facades\OpenAIFacade;

class OpenAIService {
    public const MODEL = Model::ADA_002;
    public const MAX_TOKENS_COUNT = 8191;

    public function createEmbedding(string $content) {

        abort_if(
            OpenAIFacade::countTokens(self::MODEL, $content) > self::MAX_TOKENS_COUNT,
            400,
            __('The maximum number of tokens has been exceeded!')
        );

        $embeddingContent = OpenAIFacade::createEmbedding(self::MODEL, $content)->getEmbedding();

        if (!ElasticsearchFacade::isIndexExists()) {
            ElasticsearchFacade::createIndex();
        }

        abort_if(
            !ElasticsearchFacade::isIndexExists(),
            400,
            'Failed to create an index!'
        );

        ElasticsearchFacade::createDocument($embeddingContent, $content);
        return $embeddingContent;
    }
}
