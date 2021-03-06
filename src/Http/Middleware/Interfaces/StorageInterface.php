<?php

namespace MalvikLab\LaravelHttpLogger\Http\Middleware\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface StorageInterface
{
    public function exec(Request $request, $response, string $requestContent, string $responseContent, int $executionTime);
}
