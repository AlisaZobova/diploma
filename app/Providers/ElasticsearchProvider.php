<?php

namespace App\Providers;

use App\Facades\Elasticsearch;
use Illuminate\Support\ServiceProvider;

class ElasticsearchProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('ElasticsearchAccessor', function () {
            return $this->app->make(Elasticsearch\Main\Accessor::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
