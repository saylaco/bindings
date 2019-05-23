<?php

namespace Sayla\Support\Bindings;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BindingProvider
{
    private static $defaultOptions = [];
    protected $abstracts = [];
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
    protected function configureOptions($optionsResolver): void
    {

    }

    /**
     * @return string[]
     */
    public function getBindingAliases(): array
    {
        return array_keys($this->getBindings());
    }

    public function getBindingName(string $alias)
    {
        $details = $this->getBindings()[$alias];
        return $details['name'] ?? $details[0] ?? null;
    }

    /**
     * @return array
     */
    public function getBindingNames(): array
    {
        return array_keys($this->getBindings());
    }

    /**
     * Returns an array of binding to be added to a container
     * format:
     *  [<alias:string> => [
     *      <abstractName:string>,
     *      [resolverCallback:Closure],
     *      [bootCallback:Closure],
     *      [singleton:bool]
     *      ]
     *  ];
     * resolver only: ['bookFactory' => [
     *      BookFactory::class,
     *      function(){ return new BookFactory(); }
     *      ]
     *  ];
     * resolver and booter: ['bookFactory' => [
     *      BookFactory::class,
     *      function(){ return new BookFactory(); },
     *      function($container){ $container->get(BookFactory::class)->requireAuthorLastName(); },
     *      ]
     *  ];
     * resolver and booter using alias: ['bookFactory' => [
     *      BookFactory::class,
     *      function(){ return new BookFactory(); },
     *      function($container, string $qualifiedAlias){ $container->get($qualifiedAlias)->requireAuthorLastName(); },
     *      ]
     *  ];
     * @return array
     */
    protected abstract function getBindingSet(): array;

    public function getBooter(string $alias): ?\Closure
    {
        $details = $this->getBindings()[$alias];
        return $details['boot'] ?? $details[2] ?? null;
    }

    public function getResolver(string $alias)
    {
        $details = $this->getBindings()[$alias];
        return $details['resolve'] ?? $details[1] ?? null;
    }

    public function isSingleton(string $alias): bool
    {
        return $this->getBindings()[$alias][3] ?? $this->getBindings()[$alias]['singleton'] ?? false;
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
            $this->bindings = $this->getBindingSet();
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
