<?php

namespace Prophets\WPBase;

class Base
{
    /**
     * Singleton
     * @var Base
     */
    static protected $instance;

    /**
     * @var Config
     */
    protected $config;

    /**
     * App constructor.
     * @param bool $init
     */
    public function __construct($init = false)
    {
        if ($init !== true && self::$instance === null) {
            self::$instance = new self(true);
        }
        return self::$instance;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Initialize config and providers.
     *
     * @param string $configPath
     */
    public function run($configPath)
    {
        $config = [];

        if (is_dir($configPath)) {
            foreach (new \DirectoryIterator($configPath) as $file) {
                if ($file->isDot()) {
                    continue;
                }
                $config[$file->getBasename('.php')] = require $file->getRealPath();
            }
        }
        $this->config = new Config($config);

        foreach (array_keys($this->config->all()) as $key) {
            $this->addHooks('action', $this->config->get($key . '.actions', []));
            $this->addHooks('filter', $this->config->get($key . '.filters', []));
        }
    }

    /**
     * Simple wrapper for WP add_action|add_filter.
     *
     * @param string $type
     * @param array $hook
     * @internal param string $hook
     * @return $this
     */
    public function addHook($type, array $hook)
    {
        if (!in_array($type, ['action', 'filter'])) {
            throw new \InvalidArgumentException('Not a valid hook type: ' . $type);
        }
        if (!isset($hook['name'])) {
            throw new \InvalidArgumentException('Hook name is not defined');
        }
        $method = 'add_' . $type;
        $callable = $hook['use'];

        /**
         * Make callable array
         * @todo make it possible to define constructor arguments
         */
        if (is_array($callable)) {
            $className = $callable[0];
            $classMethod = $callable[1] ?? 'init';
            $callable = [new $className($this), $classMethod];
        }
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException('Not a callable hook.');
        }
        $args = [$hook['name'], $callable];

        if (isset($hook['params']) && is_array($hook['params'])) {
            foreach ($hook['params'] as $param) {
                $args[] = $param;
            }
        }
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
