<?php

namespace App\Facades\OpenAI\Main;

use App\Facades\OpenAI\Contracts;
use App\Facades\OpenAI\Enums;
use Illuminate\Support\Facades\Process;
use OpenAI;
use OpenAI\Client;

class Accessor implements Contracts\OpenAIContract
{
    private Client $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.api_key'));
    }

    public function createEmbedding(Enums\Model $model, string $input): Contracts\EmbeddingContract
    {
        $response = $this->client->embeddings()->create([
            'model' => $model->value,
            'input' => $input,
        ]);

        return new Embedding(
            $response->embeddings[0]->embedding,
            $response->usage->promptTokens,
            $response->usage->totalTokens
        );
    }

    public function countTokens(Enums\Model $model, string $input): int
    {
        $result = Process::run(
            'python3 '
            .base_path()
            .'/storage/scripts/count_tokens.py '
            .'cl100k_base '
            .'"'.$input.'"'
        );

        return intval($result->output());
    }
}
