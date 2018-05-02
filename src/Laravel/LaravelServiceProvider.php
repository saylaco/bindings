<?php

namespace Sayla\Support\Bindings\Laravel;

use Illuminate\Support\ServiceProvider;


abstract class LaravelServiceProvider extends ServiceProvider
{
    private $bindingProvider;

    protected abstract function getBindingProvider();

    public function boot()
    {
        $this->bindingRegistrar()->boot($this->bindingProvider());
    }

    /**
     * @return \Sayla\Support\Bindings\Laravel\LaravelRegistrar
     */
    protected function bindingRegistrar()
    {
        return LaravelRegistrar::getInstance($this->app);
    }

    private function bindingProvider()
    {
        return $this->bindingProvider ?? $this->bindingProvider = $this->makeBindingProvider();
    }

    /**
     * @return \Sayla\Support\Bindings\BindingProvider
     */
    final public function makeBindingProvider()
    {
        return $this->getBindingProvider();
    }

    public function provides()
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
    public function register()
    {
        $this->bindingRegistrar()->register($this->bindingProvider());
    }
}
