<?php

namespace App\Message\Query;

class SearchQuery
{
    private string $term;

    public function __construct(string $term)
    {
        $this->term = $term;
    }

    public function getTerm(): string
    {
        return $this->term;
    }
}