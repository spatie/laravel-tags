<?php

use Illuminate\Support\Str;

return [

    /*
     * The given function generates a URL friendly "slug" from the tag name property before saving it.
     */
    'slugger' => function (string $title) {
        return Str::slug($title, '-');
    },

];
