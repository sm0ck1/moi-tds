<?php

namespace App\Helpers;


class UniqUserHash
{

    private array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function generate(): string | null
    {
        return md5(implode('', $this->params));
    }

}
