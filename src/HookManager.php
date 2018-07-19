<?php

namespace Prophets\WPBase;

class HookManager
{
    /**
     * Simple wrapper for WP add_action|add_filter.
     *
     * @param string $type
     * @param array $hook
     *
     * @internal param string $hook
     * @return HookManager
     */
    public function addHook($type, array $hook)
    {
        if (! in_array($type, ['action', 'filter'])) {
            throw new \InvalidArgumentException('Not a valid hook type: ' . $type);
        }
        if (! isset($hook['name'])) {
            throw new \InvalidArgumentException('Hook name is not defined');
        }
        $method   = 'add_' . $type;
        $callable = $hook['use'];

        /**
         * Make callable array
         * @todo make it possible to define constructor arguments
         */
        if (is_array($callable)) {
            $className   = $callable[0];
            $classMethod = $callable[1] ?? 'init';
            $callable    = [new $className($this), $classMethod];
        }
        if (! is_callable($callable)) {
            throw new \InvalidArgumentException('Not a callable hook.');
        }
        $reflection = is_array($callable)
            ? new \ReflectionMethod($callable[0], $callable[1])
            : new \ReflectionFunction($callable);
        $args = [
            $hook['name'],
            $callable,
            $hook['priority'] ?? 10,
            $reflection->getNumberOfParameters()
        ];

        call_user_func_array($method, $args);

        return $this;
    }

    /**
     * Add multiple hooks.
     *
     * @param $type
     * @param array $hooks
     */
    public function addHooks($type, array $hooks)
    {
        foreach ($hooks as $hook) {
            $this->addHook($type, $hook);
        }
    }
}
