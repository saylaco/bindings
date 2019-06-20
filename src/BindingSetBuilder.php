<?php

namespace Sayla\Support\Bindings;

class BindingSetBuilder
{
    private $_current = null;
    private $aliasPrefix = null;
    private $bindings = [];

    /**
     * @param string $aliasPrefix
     * @return $this
     */
    public function __construct(string $aliasPrefix = null)
    {
        $this->aliasPrefix = $aliasPrefix;
    }

    public static function make(string $aliasPrefix = null): self
    {
        return new self($aliasPrefix);
    }

    public function __get($key): string
    {
        return $this->getAlias($key);
    }

    public function add(string $key, string $name, \Closure $resolver = null)
    {
        $this->_current = $key;
        $this->bindings[$key] = [
            'name' => $name,
            'alias' => $this->getAlias($key),
            'resolver' => $resolver ? $resolver->bindTo($this) : $resolver,
            'booter' => null,
            'singleton' => true
        ];
        return $this;
    }

    public function addInstance(string $key, string $name, \Closure $resolver = null)
    {
        return $this->add($key, $name, $resolver)->asSingleton(false);
    }

    public function asSingleton($isSingleton = true)
    {
        $this->current()['singleton'] = $isSingleton;
        return $this;
    }

    public function booter(\Closure $callback)
    {
        $this->current()['booter'] = $booter;
        return $this;
    }

    public function getAlias(string $alias)
    {
        return $this->aliasPrefix . $alias;

    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function resolver(\Closure $callback)
    {
        $this->current()['resolver'] = $callback;
        return $this;
    }

    private function &current(): array
    {
        return $this->bindings[$this->_current];
    }

}
