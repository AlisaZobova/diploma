<?php

namespace App\Services;

use App\Facades\ElasticsearchFacade;
use App\Facades\OpenAI\Enums\Model;
use App\Facades\OpenAIFacade;

class ElasticsearchService {

    public const MODEL = Model::ADA_002;

    public function getRecommendations(string $content) {
        $queryEmbedding = OpenAIFacade::createEmbedding(self::MODEL, $content)->getEmbedding();
        return ElasticsearchFacade::getRecommendations($queryEmbedding);
    }

}
