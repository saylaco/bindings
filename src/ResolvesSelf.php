<?php

namespace Sayla\Support\Bindings;

use Illuminate\Container\Container;

trait ResolvesSelf
{
    protected abstract static function resolutionBinding(): string;

    /**
     * @return static
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public final static function resolve()
    {
        return Container::getInstance()->make(static::resolutionBinding());
    }
}