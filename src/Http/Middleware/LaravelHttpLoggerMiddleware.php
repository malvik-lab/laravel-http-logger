<?php

namespace MalvikLab\LaravelHttpLogger\Http\Middleware;

use Closure;
use Exception;
use Laravel\Lumen\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use MalvikLab\LaravelHttpLogger\Http\Middleware\Interfaces\StorageInterface;

class LaravelHttpLoggerMiddleware
{
    const NAME = 'MalvikLab - Laravel Http Logger Middleware';
    private $config;
    private $storageAdapter;

    public function __construct()
    {
        $this->config = config('malviklab-laravel-http-logger');
        $this->storageAdapter = new $this->config['storageAdapter']();

        if ( !$this->storageAdapter instanceof StorageInterface )
        {
            throw new Exception(
                self::exceptionMessage(sprintf('Invalid Storage Adapter: "%s"', $this->config['storageAdapter']))
            );
        }
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response): void
    {
        if (
            array_key_exists('requestContentTypesToHide', $this->config) AND
            is_array($this->config['requestContentTypesToHide']) AND
            in_array($request->headers->get('Content-Type'), $this->config['requestContentTypesToHide'])
        ){
            $requestContent = $this->hideContent($request);
        } else {
            $requestContent = $this->hideKeys($request);
        }

        if (
            array_key_exists('responseContentTypesToHide', $this->config) AND
            is_array($this->config['responseContentTypesToHide']) AND
            in_array($response->headers->get('Content-Type'), $this->config['responseContentTypesToHide'])
        ){
            $responseContent = $this->hideContent($response);
        } else {
            $responseContent = $this->hideKeys($response);
        }

        $executionTime = ceil((microtime(true) - LARAVEL_START) * 1000);

        $this->storageAdapter->exec($request, $response, $requestContent, $responseContent, $executionTime);
    }

    private function hideContent($obj): string
    {
        return str_replace($obj->getContent(), '', (string)$obj) . $this->config['hiddenText'];
    }

    private function hideKeys($obj): string
    {
        $text = (string)$obj;

        foreach ( $this->config['keysToHide'] as $key )
        {
            $re = sprintf('/%s:.+\n/x', $key);            
            preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);
            foreach ( $matches as $match )
            {
                $match = $match[0];
                $dataString = sprintf("%s: %s\n", $key, $this->config['hiddenText']);
                $text = str_replace($match, $dataString, $text);
            }
        }

        $re = '/\{(?:[^{}]|(?R))*\}/x';
        preg_match_all($re, $text, $matches, PREG_SET_ORDER, 0);

        foreach ( $matches as $match )
        {
            $match = $match[0];
            $data = json_decode($match, true);
            if ( is_array($data) )
            {
                foreach ( $data as $key => &$value )
                {
                    if ( in_array($key, $this->config['keysToHide']) )
                    {
                        $value = $this->config['hiddenText'];
                    }
                }
            }

            $dataString = json_encode($data);
            $text = str_replace($match, $dataString, $text);
        }

        return $text;        
    }

    public static function exceptionMessage(string $message): string
    {
        return sprintf('[ %s ] %s', strtoupper(self::NAME), $message);
    }
}