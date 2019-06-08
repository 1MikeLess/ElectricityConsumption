<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'api/*',

        // Categories.vue
        'db_query_add_category/*',
        'db_query_add_category_value/*',
        'db_query_delete_category/*',

        // Equipment.vue
        'db_query_add_equipment/*',
        'db_query_remove_equipment/*',

        // Users.vue
        'db_query_add_user/*',
        'db_query_delete_user/*'
    ];
}
