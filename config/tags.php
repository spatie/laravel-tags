<?php

return [

    /*
     * The given function generates a URL friendly "slug" from the tag name property before saving it.
     * Defaults to Str::slug (https://laravel.com/docs/5.8/helpers#method-str-slug)
     */
    'slugger' => null,

    /*
     * If using a different DB connection, specify it here
     */
    'storage' => [
        'database' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
        ],
    ],
];
