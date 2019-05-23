<?php

namespace Sayla\Support\Bindings;

use Illuminate\Container\Container;

trait ResolvesSelf
{
    protected abstract static function resolutionBinding(): string;

    public static function resolve(): self
    {
        return Container::getInstance()->make(static::resolutionBinding());
    }
}