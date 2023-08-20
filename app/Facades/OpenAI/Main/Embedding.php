<?php

namespace App\Facades\OpenAI\Main;

use App\Facades\OpenAI\Contracts;

class Embedding implements Contracts\EmbeddingContract
{
    public function __construct(
        private ?array $embedding,
        private int $prompt_tokens,
        private int $total_tokens,
    ) {
        //
    }

    public function getEmbedding(): ?array
    {
        return $this->embedding;
    }

    public function getPromptTokens(): int
    {
        return $this->prompt_tokens;
    }

    public function getTotalTokens(): int
    {
        return $this->total_tokens;
    }
}
