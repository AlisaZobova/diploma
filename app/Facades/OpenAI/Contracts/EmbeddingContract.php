<?php

namespace App\Facades\OpenAI\Contracts;

interface EmbeddingContract
{
    public function getEmbedding(): ?array;

    public function getPromptTokens(): int;

    public function getTotalTokens(): int;
}
