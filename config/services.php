<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'openai' => [
        'testing' => (bool) env('OPENAI_TESTING', false),
        'api_key' => env('OPENAI_API_KEY'),
    ],
    'elasticsearch' => [
        'host' => env('ELASTICSEARCH_HOST', 'http://localhost:9200'),
        'username' => env('ELASTICSEARCH_AUTH_USERNAME', 'elastic'),
        'password' => env('ELASTICSEARCH_AUTH_PASSWORD'),
        'crt_path' => base_path().env('ELASTICSEARCH_CRT_PATH'),
        //        'api_key' => base_path() . env('ELASTICSEARCH_API_KEY')
    ],
];
