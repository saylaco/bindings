<?php

namespace Sayla\Support\Bindings\Laravel;

use Illuminate\Contracts\Container\Container;
use Sayla\Support\Bindings\BaseRegistrar;
use Sayla\Support\Bindings\BindingProvider;


class LaravelRegistrar extends BaseRegistrar
{
    /** @var \Illuminate\Contracts\Container\Container */
    private $container;
    protected $tags = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public static function getInstance(Container $container = null): self
    {
        return new self($container ?? \Illuminate\Container\Container::getInstance());
    }

    protected function bootProvider(BindingProvider $provider, string $alias): void
    {
        if ($this->container instanceof \Illuminate\Contracts\Foundation\Application
            && method_exists($provider, 'booted')) {
            $this->container->booted([$provider, 'booted']);
        }
        parent::bootProvider($provider, $alias);
    }

    protected function callBooter(callable $booter, string $qualifiedAlias)
    {
        $booter($this->container, $qualifiedAlias);
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param $isSingleton
     * @param $abstract
     * @param $resolver
     * @param $alias
     */
    protected function registerBinding(bool $isSingleton, string $abstract, $resolver = null, ?string $alias)
    {
        if ($alias) {
            $this->container->alias($abstract, $alias);
        }
        if ($isSingleton) {
            $this->registerSingletonBinding($abstract, $resolver);
        } else {
            $this->registerSimpleBinding($abstract, $resolver);
        }
    }

    public function register(BindingProvider ...$providers)
    {
        parent::register(...$providers);
        if (!empty($this->tags)) {
            $this->registerTags($this->abstracts, $this->tags);
        }
    }

    protected function registerProvider(BindingProvider $provider, string $alias): void
    {
        if ($this->container instanceof \Illuminate\Contracts\Foundation\Application
            && method_exists($provider, 'booting')) {
            $this->container->booting([$provider, 'booting']);
        }
        parent::registerProvider($provider, $alias);
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param string $abstract
     */
    protected function registerSingletonBinding(string $abstract, ?callable $resolver)
    {
        $this->container->singleton($abstract, $resolver);
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param string $abstract
     * @param callable|null $resolver
     */
    protected function registerSimpleBinding(string $abstract, ?callable $resolver)
    {
        $this->container->bind($abstract, $resolver);
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param $abstracts
     * @param $tags
     */
    protected function registerTags(array $abstracts, array $tags)
    {
        $this->container->tag($abstracts, $tags);
    }

    /**
     * @param string[] $tags
     * @return \Sayla\Support\Bindings\Laravel\LaravelRegistrar
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
        return $this;
    }
}
