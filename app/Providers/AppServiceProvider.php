<?php

namespace App\Providers;

use App\Event;
use Laravel\Cashier\Cashier;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('room_available', function ($attribute, $value, $parameters, $validator) {
            [$roomId, $startTime, $endTime] = $parameters;

            // Check if the room is available for booking
            $isAvailable = !Event::where('room_id', $roomId)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime])->whereBetween('end_time', [$startTime, $endTime]);
                })
                ->exists();

            return $isAvailable;
        });
    }
}
