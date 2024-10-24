<?php

namespace AutoDealersDigital\PhotoProcessor;

use Illuminate\Support\ServiceProvider;
use AutoDealersDigital\PhotoProcessor\Services\CloudinaryProcessing;
use AutoDealersDigital\PhotoProcessor\Services\ThumborProcessing;
use AutoDealersDigital\PhotoProcessor\Services\PhotoProcessorService;

class PhotoProcessorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/photo_processor.php', 'photo_processor'
        );

        // // Bind the individual services
        // $this->app->singleton(CloudinaryProcessing::class, function ($app) {
        //     return new CloudinaryProcessing();
        // });

        // $this->app->singleton(ThumborProcessing::class, function ($app) {
        //     return new ThumborProcessing();
        // });

        // // Bind the main processor service
        // $this->app->singleton(PhotoProcessorService::class, function ($app) {
        //     return new PhotoProcessorService(
        //         $app->make(CloudinaryProcessing::class),
        //         $app->make(ThumborProcessing::class)
        //     );
        // });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/photo_processor.php' => config_path('photo_processor.php'),
        ]);
    }
}
