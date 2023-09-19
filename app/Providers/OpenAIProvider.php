<?php

namespace App\Providers;

use App\Facades\OpenAI;
use Illuminate\Support\ServiceProvider;

class OpenAIProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (config('services.openai.testing')) {

            $this->app->bind('OpenAIAccessor', function () {
                return $this->app->make(OpenAI\Test\Accessor::class);
            });

        } else {

            $this->app->bind('OpenAIAccessor', function () {
                return $this->app->make(OpenAI\Main\Accessor::class);
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
