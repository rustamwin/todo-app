<?php

namespace App\Providers;

use App\Todo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Todo::saving(function (Todo $model) {
            if ($model->done === true) {
                $model->done_at = now();
            }
        });

        Todo::creating(function (Todo $model) {
            $model->position = Todo::all()->count() + 1;
        });

        Todo::deleted(function (Todo $todo) {
            Todo::where('parent_id', '=', $todo->id)->update(['parent_id' => null]);
        });
    }
}
