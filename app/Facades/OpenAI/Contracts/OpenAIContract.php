<?php

namespace App\Facades\OpenAI\Contracts;

use App\Facades\OpenAI\Enums;

interface OpenAIContract
{
    public function createEmbedding(Enums\Model $model, string $input): EmbeddingContract;

    public function countTokens(Enums\Model $model, string $input): int;
}
