<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Contracts\BikeFilterRepositoryInterface;
use App\Repositories\Eloquent\BikeFilterRepository;

    class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(BikeFilterRepositoryInterface::class, BikeFilterRepository::class);
    }
    public function boot()
    {
        //
    }
}
