<?php

namespace App\Providers;

use App\Domain\Users\Models\User;
use App\Domain\Users\Observers\UserObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected array $observers = [
        User::class => UserObserver::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerObservers();

        JsonResource::withoutWrapping();
    }

    /**
     * Registra observadores.
     *
     * @return void
     */
    private function registerObservers(): void
    {
        foreach ($this->observers as $model => $observer) {
            $model::observe($observer);
        }
    }
}
