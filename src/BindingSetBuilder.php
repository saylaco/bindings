<?php

namespace Sayla\Support\Bindings;

class BindingSetBuilder
{
    private $_current = null;
    private $bindings = [];

    public static function make(): self
    {
        return new self();
    }

    public function add(string $alias, string $name, \Closure $resolver = null)
    {
        $this->_current = $alias;
        $this->bindings[$alias] = ['name' => $name, 'resolver' => $resolver, 'booter' => null, 'singleton' => true];
        return $this;
    }

    public function addInstance(string $alias, string $name, \Closure $resolver = null)
    {
        return $this->add($alias, $name, $resolver)->asSingleton(false);
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
