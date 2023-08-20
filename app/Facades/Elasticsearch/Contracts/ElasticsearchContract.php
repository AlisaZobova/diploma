<?php

namespace App\Facades\Elasticsearch\Contracts;

interface ElasticsearchContract
{
    public function createDocument(array $embedding, string $originalText);

    public function getRecommendations(array $queryEmbedding, int $resultsCount = 10);
}
