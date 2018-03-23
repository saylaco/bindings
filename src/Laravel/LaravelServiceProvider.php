<?php

namespace Sayla\Support\Bindings\Laravel;

use Illuminate\Support\ServiceProvider;
use Sayla\Support\Bindings\Registrar;


abstract class LaravelServiceProvider extends ServiceProvider
{
    private $bindingProvider;

    final public function boot()
    {
        $this->bindingRegistrar()->boot($this->bindingProvider());
    }

    protected function bindingRegistrar(): LaravelRegistrar
    {
        return LaravelRegistrar::getInstance($this->app);
    }

    private function bindingProvider()
    {
        return $this->bindingProvider ?? $this->bindingProvider = $this->getBindingProvider();
    }

    protected abstract function getBindingProvider();

    final public function provides()
    {
        return array_unique([
            $this->bindingRegistrar()->getBindingAliases(),
            $this->bindingRegistrar()->getAbstractNames()
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    final public function register()
    {
        $this->bindingRegistrar()->register($this->bindingProvider());
    }
}
