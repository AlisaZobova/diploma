<?php

namespace App\Services;

use App\Facades\ElasticsearchFacade;
use App\Facades\OpenAI\Enums\Model;
use App\Facades\OpenAIFacade;

class ElasticsearchService {

    public const MODEL = Model::ADA_002;

    public function getRecommendations(string|null $searchRequest, int $resultsCount = 10) {
        abort_if(
            !ElasticsearchFacade::isIndexExists(),
            400,
            'Index does not exist!'
        );

        if (!$searchRequest) {
            return ElasticsearchFacade::getAllProducts();
        }

        $queryEmbedding = OpenAIFacade::createEmbedding(self::MODEL, $searchRequest)->getEmbedding();

        return ElasticsearchFacade::getRecommendations($queryEmbedding, $resultsCount);
    }

    public function deleteDocument(string $documentId)
    {
        return ElasticsearchFacade::deleteDocument($documentId);
    }

}
