<?php

namespace Sayla\Support\Bindings\Laravel;

use Illuminate\Support\ServiceProvider;


abstract class LaravelServiceProvider extends ServiceProvider
{
    private $bindingProvider;
    private $registrar;

    /**
     * @return \Sayla\Support\Bindings\Laravel\LaravelRegistrar
     */
    protected function bindingRegistrar()
    {
        return $this->registrar ?? $this->registrar = LaravelRegistrar::getInstance($this->app);
    }

    public function boot()
    {
        $this->bindingRegistrar()->boot($this->bindingProvider());
    }

    protected abstract function getBindingProvider();

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
            $this->bindingRegistrar()->getAllAliases(),
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

    private function bindingProvider()
    {
        return $this->bindingProvider ?? $this->bindingProvider = $this->makeBindingProvider();
    }
}
