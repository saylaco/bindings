<?php

namespace Sayla\Support\Bindings;

abstract class BindingProvider
{
    protected $abstracts = [];
    private $bindings;

    /**
     * @return string[]
     */
    public function getBindingAliases(): array
    {
        return array_keys($this->getBindings());
    }

    private function getBindings(): array
    {
        return $this->bindings ?? $this->bindings = $this->getBindingSet();
    }

    /**
     * Returns an array of binding to be added to a container
     * format:
     *  [<alias:string> => [
     *      <abstractName:string>,
     *      [resolverCallback:Closure],
     *      [bootCallback:Closure],
     *      [isSingleton:bool]
     *      ]
     *  ];
     * registrar only: ['bookFactory' => [
     *      BookFactory::class,
     *      function(){ return new BookFactory(); }
     *      ]
     *  ];
     * registrar and booter: ['bookFactory' => [
     *      BookFactory::class,
     *      function(){ return new BookFactory(); },
     *      function($container){ $container->get(BookFactory::class)->requireAuthorLastName(); },
     *      ]
     *  ];
     * registrar and booter using alias: ['bookFactory' => [
     *      BookFactory::class,
     *      function(){ return new BookFactory(); },
     *      function($container, string $qualifiedAlias){ $container->get($qualifiedAlias)->requireAuthorLastName(); },
     *      ]
     *  ];
     * @return array
     */
    protected abstract function getBindingSet(): array;

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
}
