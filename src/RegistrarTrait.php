<?php

namespace Sayla\Support\Bindings;

trait RegistrarTrait
{

    /**  @var string */
    private $aliasPrefix = '';
    /** @var string[] */
    private $excluded = [];
    /** @var string[] */
    private $included = [];

    public function exclude(string $alias)
    {
        $this->excluded[] = $alias;
        return $this;
    }

    /**
     * @return string
     */
    public function getAliasPrefix(): string
    {
        return $this->aliasPrefix;
    }

    /**
     * @param string $aliasPrefix
     */
    public function setAliasPrefix(string $aliasPrefix)
    {
        $this->aliasPrefix = $aliasPrefix;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getIncludedBindingKeys(BindingProvider $provider)
    {
        $bindingKeys = $provider->getBindingKeys();
        if (empty($this->included)) {
            if (!empty($this->excluded)) {
                return array_diff($bindingKeys, $this->excluded);
            }
            return $bindingKeys;
        }
        return $this->included;
    }

    public function include(string $alias)
    {
        $this->included[] = $alias;
        return $this;
    }

}
