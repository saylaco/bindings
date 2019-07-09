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
        foreach ($providers as $provider) {
            $provider->setAliasPrefix($this->aliasPrefix);
            foreach ($this->getIncludedBindingKeys($provider) as $key) {
                $this->bootProvider($provider, $key);
            }
        }
    }

    /**
     * @param $provider
     * @param $alias
     */
    protected function bootProvider(BindingProvider $provider, string $alias): void
    {
        $binding = $provider->getBinding($alias);
        $booter = $binding['booter'];
        if ($booter != null) {
            $this->callBooter($booter, $binding['alias']);
        }
    }

    protected function callBooter(callable $booter, string $qualifiedAlias)
    {
        $booter($qualifiedAlias);
    }

    /**
     * @param \Sayla\Support\Bindings\BindingProvider $provider
     * @return striing[]
     */
    public function getAliases(BindingProvider $provider)
    {
        return $this->aliases[spl_object_id($provider)] ?? [];
    }

    /**
     * @return striing[]
     */
    public function getAllAliases()
    {
        $aliases = [];
        foreach ($this->aliases as $_aliases) {
            $aliases = array_merge($aliases, $_aliases);
        }
        return $aliases;
    }

    public function register(BindingProvider ...$providers)
    {
        foreach ($providers as $provider) {
            $provider->setAliasPrefix($this->getAliasPrefix());
            foreach ($this->getIncludedBindingKeys($provider) as $key) {
                $this->registerProvider($provider, $key);
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
    protected function registerProvider(BindingProvider $provider, string $key): void
    {
        $binding = $provider->getBinding($key);
        $this->abstracts[] = $abstract = $binding['name'];
        $this->aliases[spl_object_id($provider)][$key] = $binding['alias'];
        $isSingleton = $binding['singleton'] || $this->useSingletons;
        $this->registerBinding($binding['singleton'], $abstract, $binding['resolver'], $binding['alias']);
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
