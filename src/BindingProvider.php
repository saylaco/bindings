<?php

namespace Sayla\Support\Bindings;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BindingProvider
{
    private static $defaultOptions = [];
    protected $abstracts = [];
    private $aliasPrefix = '';
    private $bindings;
    private $options = [];
    private $unresolvedOptions = [];

    final public function __construct()
    {
    }

    /**
     * @param array $options
     * @param string|null $className
     * @param bool $override
     */
    final public static function setDefaultOptions(array $options, string $className = null, bool $override = false)
    {
        $className = $className ?? static::class;
        if ($override || !isset(self::$defaultOptions[$className])) {
            self::$defaultOptions[$className] = $options;
        } else {
            self::$defaultOptions[$className] = array_merge(self::$defaultOptions[$className], $options);
        }
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    protected abstract function configureOptions($optionsResolver): void;

    /**
     * @param \Sayla\Support\Bindings\BindingSetBuilder $setBuilder
     */
    protected abstract function defineBindings($setBuilder);

    /**
     * @return string
     */
    public function getAliasPrefix(): string
    {
        return $this->aliasPrefix;
    }

    /**
     * @param string $aliasPrefix
     * @return $this
     */
    public function setAliasPrefix(string $aliasPrefix)
    {
        $this->aliasPrefix = $aliasPrefix;
        return $this;
    }

    /**
     * @param string $alias
     * @return array
     */
    public function getBinding(string $alias): array
    {
        return $this->getBindings()[$alias];
    }

    /**
     * @return string[]
     */
    public function getBindingKeys(): array
    {
        return array_keys($this->getBindings());
    }

    /**
     * @return OptionsResolver
     */
    protected function makeOptionsResolver(): OptionsResolver
    {
        return new OptionsResolver();
    }

    public function mergeOptions(array $options)
    {
        $this->unresolvedOptions = array_merge($this->options, $this->unresolvedOptions, $options);
        return $this;
    }

    final public function option(string $key)
    {
        return $this->options[$key];
    }

    public function setOption($key, $value)
    {
        $this->unresolvedOptions[$key] = $value;
        return $this;
    }

    final public function setOptions(array $options)
    {
        $this->unresolvedOptions = $options;
        return $this;
    }

    private function getBindings(): array
    {
        if (!isset($this->bindings)) {
            if (isset(self::$defaultOptions[static::class])) {
                $options = array_merge(self::$defaultOptions[static::class], $this->unresolvedOptions);
            } else {
                $options = $this->unresolvedOptions;
            }
            $this->options = $this->getOptionsResolver()->resolve($options);
            $setBuilder = BindingSetBuilder::make($this->getAliasPrefix());
            $this->defineBindings($setBuilder);
            $this->bindings = $setBuilder->getBindings();
        }
        return $this->bindings;
    }

    private function getOptionsResolver()
    {
        $optionsResolver = $this->makeOptionsResolver();
        $this->configureOptions($optionsResolver);
        return $optionsResolver;
    }
}
