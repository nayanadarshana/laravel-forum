<?php

namespace App\Providers;


use App\Interfaces\Post\PostInterface;
use App\Interfaces\User\UserInterface;
use App\Repositories\Post\PostRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;



class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(PostInterface::class, PostRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
