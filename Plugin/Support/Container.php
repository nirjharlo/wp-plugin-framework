<?php
namespace NirjharLo\WP_Plugin_Framework\Support;

/**
 * Simple dependency injection container inspired by Laravel's container.
 */
class Container
{
    /**
     * Registered bindings.
     *
     * @var array<string, callable>
     */
    protected $bindings = [];

    /**
     * Resolved singleton instances.
     *
     * @var array<string, object>
     */
    protected $instances = [];

    /**
     * Register a binding with the container.
     *
     * @param string   $abstract
     * @param callable $concrete
     *
     * @return void
     */
    public function bind($abstract, callable $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Register a singleton binding with the container.
     *
     * @param string   $abstract
     * @param callable $concrete
     *
     * @return void
     */
    public function singleton($abstract, callable $concrete)
    {
        $this->bindings[$abstract] = function ($container) use ($concrete, $abstract) {
            if (!isset($this->instances[$abstract])) {
                $this->instances[$abstract] = $concrete($container);
            }

            return $this->instances[$abstract];
        };
    }

    /**
     * Resolve an instance from the container.
     *
     * @param string $abstract
     *
     * @return mixed
     */
    public function make($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract]($this);
        }

        return new $abstract();
    }
}
