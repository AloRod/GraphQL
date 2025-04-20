<?php

namespace App\GraphQL\Queries;

class Hello
{
    public function __invoke($_, array $args)
    {
        return "prueba graphql";

    }
}