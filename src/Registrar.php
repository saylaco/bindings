<?php

namespace Sayla\Support\Bindings;

/**
 * Interface Registrar
 * @package Sayla\Support\Bindings
 */
interface Registrar
{

    /**
     * @param \Sayla\Support\Bindings\BindingProvider[] ...$providers
     * @return void
     */
    public function boot(BindingProvider ...$providers);

    /**
     * @param string $alias
     * @return mixed
     */
    public function exclude(string $alias);

    /**
     * @return string|null
     */
    public function getAliasPrefix(): ?string;

    /**
     * @return string[]
     */
    public function getIncludedBindingKeys(BindingProvider $provider);

    /**
     * @param string $alias
     * @return mixed
     */
    public function include(string $alias);

    /**
     * @param \Sayla\Support\Bindings\BindingProvider[] ...$providers
     */
    public function register(BindingProvider ...$providers);

    /**
     * @param string $aliasPrefix
     * @return $this
     */
    public function setAliasPrefix(string $aliasPrefix);

}
