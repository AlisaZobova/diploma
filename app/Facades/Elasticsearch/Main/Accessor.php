<?php

namespace App\Facades\Elasticsearch\Main;

use App\Facades\Elasticsearch\Contracts;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;

class Accessor implements Contracts\ElasticsearchContract
{

    private Client $client;
    private string $indexName;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('services.elasticsearch.host')])
//            ->setBasicAuthentication(config('services.elasticsearch.username'), config('services.elasticsearch.password'))
            ->setCABundle(config('services.elasticsearch.crt_path'))
            ->setApiKey(config('services.elasticsearch.api_key'))
            ->build();

        $this->indexName = 'embeddings';

        try {
            $this->client->indices()->get(['index' => $this->indexName]);
        } catch (ClientResponseException|ServerResponseException|Exception $e) {
            try {
                $this->client->indices()->create([
                    'index' => $this->indexName,
                    'body' => [
                        'mappings' => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' => [
                                "embedding" => [
                                    "type" => "dense_vector",
                                    "index" => true,
                                    "dims" => 1536,
                                    "similarity" => "dot_product"
                                ],
                                "original_text" => [
                                    "type" => "keyword"
                                ]
                            ]
                        ]
                    ]
                ]);
            } catch (ClientResponseException|ServerResponseException|Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function createDocument(array $embedding, string $originalText)
    {
        $params = [
            'index' => $this->indexName,
            'body' => [
                'embedding' => $this->normalizeVector($embedding),
                'original_text' => $originalText
            ]
        ];

        try {
            return $this->client->index($params);
        } catch (ClientResponseException|ServerResponseException|Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRecommendations(array $queryEmbedding, int $resultsCount = 10)
    {
        $params = [
            'index' => $this->indexName,
            'body' => [
                'query' => [
                    'script_score' => [
                        'query' => [
                            'match_all' => new \stdClass()
                        ],

                        "script" => [
                            "source" => "cosineSimilarity(params.query_vector, 'embedding') + 1.0",
                            "params" => ["query_vector" => $this->normalizeVector($queryEmbedding)]
                        ]
                    ]
                ],
                'size' => $resultsCount,
                '_source' => ['original_text', 'embedding'], // Include any other fields you want in the response
                'sort' => [
                    '_score' => ['order' => 'desc']
                ]
            ]
        ];

        $response = $this->client->search($params);

        return $response['hits']['hits'];

    }

    public function normalizeVector(array $vector): array
    {
        $sumOfSquares = 0.0;

        foreach ($vector as $component) {
            $sumOfSquares += $component * $component;
        }

        $length = sqrt($sumOfSquares);

        if ($length > 0) {
            $normalizedVector = array_map(function ($component) use ($length) {
                return $component / $length;
            }, $vector);

            return $normalizedVector;
        }

        return $vector;
    }

}