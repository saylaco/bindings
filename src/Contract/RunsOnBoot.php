<?php

namespace Sayla\Support\Bindings\Contract;

interface RunsOnBoot
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param string[] $aliases
     */
    public function booting($container, $aliases): void;
}
