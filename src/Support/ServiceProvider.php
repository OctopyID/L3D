<?php

namespace Octopy\L3D\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var array
     */
    protected array $listeners = [];

    /**
     * @var array<Model, class-string>
     */
    protected array $observers = [];

    /**
     * @return void
     */
    public function register() : void
    {
        $this->booting(function () {
            // register events listeners
            foreach ($this->listeners as $event => $listeners) {
                foreach (array_unique($listeners, SORT_REGULAR) as $listener) {
                    Event::listen($event, $listener);
                }
            }

            /**
             * @var Model $model
             */
            foreach ($this->observers as $model => $observers) {
                $model::observe($observers);
            }
        });

        // register routes
        foreach (Arr::wrap($this->routes()) as $route) {
            $this->loadRoutesFrom($route);
        }
    }

    /**
     * @param  string $path
     * @param  array  $value
     * @return void
     */
    protected function mergeConfigArray(string $path, array $value) : void
    {
        config([
            $path => array_merge(config($path), $value),
        ]);
    }

    /**
     * @return string|array
     */
    protected function routes() : string|array
    {
        return [];
    }
}