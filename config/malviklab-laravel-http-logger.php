<?php

return [
    'storageAdapter' => MalvikLab\LaravelHttpLogger\Http\Middleware\Adapters\DbAdapter::class,
    'hiddenText' => '[ *** HIDDEN *** ]',
    'keysToHide' => [
        'Authorization',
        'password',
        'token',
    ],
];