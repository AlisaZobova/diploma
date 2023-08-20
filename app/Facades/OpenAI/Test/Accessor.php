<?php

namespace App\Facades\OpenAI\Test;

use App\Facades\OpenAI\Contracts;
use App\Facades\OpenAI\Enums;
use Illuminate\Support\Facades\Process;

class Accessor implements Contracts\OpenAIContract
{
    public const MODEL = Enums\Model::ADA_002;

    public function createEmbedding(Enums\Model $model, string $input): Contracts\EmbeddingContract
    {
        $embedding = [];
        for ($i = 0; $i < 1536; $i++) {
            $embedding[] = fake()->randomFloat(10, -1, 1);
        }

        $prompt_tokens = $this->countTokens(self::MODEL, $input);

        return new Embedding(
            $embedding,
            $prompt_tokens,
            $prompt_tokens
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
