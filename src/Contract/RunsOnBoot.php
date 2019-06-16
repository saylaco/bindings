<?php

namespace Sayla\Support\Bindings\Contract;

interface RunsOnBoot
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param string[] $qualifiedAliases
     */
    public function booting($container, $qualifiedAliases): void;
}
