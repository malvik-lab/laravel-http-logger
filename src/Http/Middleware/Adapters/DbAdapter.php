<?php

namespace MalvikLab\LaravelHttpLogger\Http\Middleware\Adapters;

use MalvikLab\LaravelHttpLogger\Http\Middleware\Interfaces\StorageInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Log;
use Exception;
use MalvikLab\LaravelHttpLogger\Models\RequestLog;

class DbAdapter implements StorageInterface
{
    public function exec(Request $request, $response, string $requestContent, string $responseContent, int $executionTime)
    {
        try {
            $requestLog = new RequestLog();
            $requestLog->request_secure = $request->secure();
            $requestLog->request_method = $request->method();
            $requestLog->request_uri = $request->getRequestUri();
            $requestLog->request_ip = $request->ip();
            $requestLog->request_user_agent = $request->userAgent();
            $requestLog->request_content_type = $request->headers->get('Content-Type');
            $requestLog->response_content_type = $response->headers->get('Content-Type');
            $requestLog->execution_time = $executionTime;
            $requestLog->request = $requestContent;
            $requestLog->response = $responseContent;
            $requestLog->datetime = Carbon::now();
            $requestLog->save();
        } catch(Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
