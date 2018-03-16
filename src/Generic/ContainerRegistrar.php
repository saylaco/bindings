<?php

namespace Sayla\ContainerBindings\Ellipse;

use Psr\Container\ContainerInterface;
use Sayla\ContainerBindings\BindingProvider;
use Sayla\ContainerBindings\Registrar;
use Sayla\ContainerBindings\RegistrarTrait;

abstract class ContainerRegistrar implements Registrar
{
    use RegistrarTrait;
    /** @var ContainerInterface */
    private $container;

    /**
     * @param \Sayla\ContainerBindings\BindingProvider[] ...$providers
     * @return void
     */
    public function boot(BindingProvider ...$providers)
    {
        foreach ($providers as $provider) {
            foreach ($this->getIncludedBindingAliases($provider) as $alias) {
                if ($booter = $provider->getBooter($alias)) {
                    $this->callBooter($booter, $this->aliasPrefix . $alias);
                }
            }
        }
    }

    /**
     * @param \Sayla\ContainerBindings\BindingProvider[] ...$providers
     * @return void
     */
    public function register(BindingProvider ...$providers)
    {
        foreach ($providers as $provider) {
            $this->addResolvers($this->getProviderResolvers($provider), $provider);
        }
    }

    protected function callBooter(callable $booter, string $qualifiedAlias)
    {
        $booter($this->container, $qualifiedAlias);
    }

    /**
     * Add resolvers to container
     * @param callable[] $resolvers
     * @param \Sayla\ContainerBindings\BindingProvider $provider
     * @return void
     */
    protected abstract function addResolvers(array $resolvers, BindingProvider $provider);

    /**
     * @param \Sayla\ContainerBindings\BindingProvider $provider
     * @return callable[]
     */
    protected function getProviderResolvers(BindingProvider $provider): array
    {
        $resolvers = [];
        foreach ($this->getIncludedBindingAliases($provider) as $alias) {
            $resolver = $provider->getResolver($alias);
            $resolvers[$this->aliasPrefix . $alias] = $resolver;
            if ($name = $provider->getBindingName($alias)) {
                $resolvers[$name] = $resolver;
            }
        }
        return $resolvers;
    }
}
