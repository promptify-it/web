<?php

namespace App\Http\Middleware;

class TrustProxies extends \Illuminate\Http\Middleware\TrustProxies
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<string>
     */
    protected $proxies = [
        '*',
    ];
}
