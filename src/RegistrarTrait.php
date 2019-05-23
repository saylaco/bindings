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
     * @return string[]
     */
    public function getIncludedBindingAliases(BindingProvider $provider)
    {
        $bindingAliases = $provider->getBindingNames();
        if (empty($this->included)) {
            if (!empty($this->excluded)) {
                return array_diff($bindingAliases, $this->excluded);
            }
            return $bindingAliases;
        }
        return $this->included;
    }

    public function include(string $alias)
    {
        $this->included[] = $alias;
        return $this;
    }

    /**
     * @param string $aliasPrefix
     */
    public function setAliasPrefix(string $aliasPrefix)
    {
        $this->aliasPrefix = $aliasPrefix;
        return $this;
    }

}
