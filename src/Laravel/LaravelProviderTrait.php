<?php

namespace Sayla\Support\Bindings\Laravel;
/**
 * Trait LaravelProviderTrait
 * @package Sayla\BindingProviders
 * @mixin \Sayla\Support\Bindings\BindingProvider
 */
trait LaravelProviderTrait
{

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param $isSingleton
     * @param $abstract
     * @param $resolver
     * @param $alias
     */
    public function registerBinding($container, $isSingleton, $abstract, $resolver, $alias)
    {
        if ($alias) {
            $container->alias($abstract, $alias);
        }
        if ($isSingleton) {
            $this->registerSingletonBinding($container, $abstract, $resolver);
        } else {
            $this->registerSimpleBinding($container, $abstract, $resolver);
        }
        $this->afterBind($abstract, $alias);
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param string $abstract
     */
    protected function registerSingletonBinding($container, string $abstract, ?callable $resolver)
    {
        $container->singleton($abstract, $resolver);
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param string $abstract
     * @param callable|null $resolver
     */
    protected function registerSimpleBinding($container, string $abstract, ?callable $resolver)
    {
        $container->bind($abstract, $resolver);
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param $abstracts
     * @param $tags
     */
    public function registerTags($container, $abstracts, $tags): void
    {
        $container->tag($abstracts, $tags);
    }
}
