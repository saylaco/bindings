<?php

namespace Sayla\Support\Bindings;

abstract class BaseRegistrar implements Registrar
{
    use RegistrarTrait;
    protected $abstracts = [];
    protected $aliases = [];
    protected $useSingletons = false;

    public function boot(BindingProvider ...$providers)
    {
        foreach ($providers as $provider)
            foreach ($this->getIncludedBindingAliases($provider) as $alias) {
                $this->bootProvider($provider, $alias);
            }
    }

    /**
     * @param $provider
     * @param $alias
     */
    protected function bootProvider(BindingProvider $provider, string $alias): void
    {
        $booter = $provider->getBinding($alias)['booter'];
        if ($booter != null) {
            $this->callBooter($booter, $this->aliasPrefix . $alias);
        }
    }

    protected function callBooter(callable $booter, string $qualifiedAlias)
    {
        $booter($qualifiedAlias);
    }

    public function register(BindingProvider ...$providers)
    {
        foreach ($providers as $provider) {
            foreach ($this->getIncludedBindingAliases($provider) as $alias) {
                $this->registerProvider($provider, $alias);
            }
        }
    }

    /**
     * @param bool $isSingleton
     * @param string $abstract
     * @param null|string $resolver
     * @param null|string $alias
     * @return mixed
     */
    protected abstract function registerBinding(bool $isSingleton, string $abstract, $resolver = null, ?string $alias);

    /**
     * @param \Sayla\Support\Bindings\BindingProvider $provider
     * @param string $alias
     */
    protected function registerProvider(BindingProvider $provider, string $alias): void
    {
        $binding = $provider->getBinding($alias);
        $this->abstracts[] = $abstract = $binding['name'] ?: $alias;
        $this->aliases[$alias] = $containerAlias = $this->aliasPrefix . $alias;
        $isSingleton = $binding['singleton'] || $this->useSingletons;
        $this->registerBinding($binding['singleton'], $abstract, $binding['resolver'], $containerAlias);
    }

    /**
     * @param bool $useSingletons
     * @return $this
     */
    public function useSingletons(bool $useSingletons = true)
    {
        $this->useSingletons = $useSingletons;
        return $this;
    }

}
