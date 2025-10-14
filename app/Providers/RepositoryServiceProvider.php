<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Eloquent\BookingRepository;

    class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
    }
    public function boot()
    {
        //
    }
}
