<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TypeRepositoryInterface;
use App\Repositories\TypeRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\ItemRequestRepositoryInterface;
use App\Repositories\ItemRequestRepository;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\ItemRepository;
use App\Repositories\MutationItemRequestRepositoryInterface;
use App\Repositories\MutationItemRequestRepository;
use App\Repositories\MaintenanceItemRequestRepositoryInterface;
use App\Repositories\MaintenanceItemRequestRepository;
use App\Repositories\RemoveItemRequestRepositoryInterface;
use App\Repositories\RemoveItemRequestRepository;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TypeRepositoryInterface::class, TypeRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ItemRequestRepositoryInterface::class, ItemRequestRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(MutationItemRequestRepositoryInterface::class, MutationItemRequestRepository::class);
        $this->app->bind(MaintenanceItemRequestRepositoryInterface::class, MaintenanceItemRequestRepository::class);
        $this->app->bind(RemoveItemRequestRepositoryInterface::class, RemoveItemRequestRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewApiDocs', function () {
            return true;
        });
    }
}
