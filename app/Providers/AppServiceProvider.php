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
                    // Check for overlapping bookings
                    $query
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<', $startTime)->where('end_time', '>', $startTime);
                        })
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<', $endTime)->where('end_time', '>', $endTime);
                        })
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->whereBetween('start_time', [$startTime, $endTime])->whereBetween('end_time', [$startTime, $endTime]);
                        });
                })
                ->doesntExist();

            return $isAvailable;
        });
    }
}
