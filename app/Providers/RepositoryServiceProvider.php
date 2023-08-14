<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Repositories\MeterReaderRepository;
use App\ServiceInterfaces\CustomerInterface;
use App\ServiceInterfaces\MeterReaderInterface;
use Carbon\Laravel\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(MeterReaderInterface::class, MeterReaderRepository::class);
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
    }

}
