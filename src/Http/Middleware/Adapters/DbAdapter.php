<?php

namespace MalvikLab\LaravelHttpLogger\Http\Middleware\Adapters;

use MalvikLab\LaravelHttpLogger\Http\Middleware\Interfaces\StorageInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Log;
use Exception;

class DbAdapter implements StorageInterface
{
    public function exec(Request $request, $response, string $requestContent, string $responseContent, int $executionTime)
    {
        try {
            DB::insert('
                INSERT LOW_PRIORITY INTO request_log (
                    request_secure,
                    request_method,
                    request_uri,
                    request_ip,
                    request_user_agent,
                    request_content_type,
                    response_content_type,
                    response_status_code,
                    execution_time,
                    request,
                    response,
                    datetime
                ) VALUES (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )',
                [
                    $request->secure(),
                    $request->method(),
                    $request->getRequestUri(),
                    $request->ip(),
                    $request->userAgent(),
                    $request->headers->get('Content-Type'),
                    $response->headers->get('Content-Type'),
                    $response->getStatusCode(),
                    $executionTime,
                    $requestContent,
                    $responseContent,
                    Carbon::now()
                ]
            );
        } catch(Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
