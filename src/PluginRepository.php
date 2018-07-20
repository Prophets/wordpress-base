<?php

namespace Prophets\WPBase;

class PluginRepository
{
    /**
     * Singleton
     *
     * @var PluginRepository
     */
    static protected $instance;

    /**
     * @var array
     */
    protected $plugins;

	/**
	 * PluginRepository constructor.
	 */
    protected function __construct() {}

	/**
	 * Prevent singleton from cloning.
	 */
	protected function __clone() {}

    /**
     * @return PluginRepository
     */
    public static function getInstance()
    {
	    if (static::$instance === null) {
		    static::$instance = new static();
	    }

	    return static::$instance;
    }

    /**
     * Register a plugin as singleton.
     *
     * @param $bootstrap
     * @param string $alias
     * @param string $configPath
     */
    public function registerPlugin($bootstrap, $alias, $configPath)
    {
        if (isset($this->plugins[$alias])) {
            throw new \RuntimeException("Plugin '$alias' is already registered.");
        }
        $this->plugins[$alias] = $bootstrap;

        $config = new Config(require $configPath);
        $hookManager = new HookManager();

        $hookManager->addHooks('action', $config->get('actions', []));
        $hookManager->addHooks('filter', $config->get('filters', []));
    }

    /**
     * Get a plugin.
     *
     * @param $alias
     *
     * @return mixed
     */
    public function getPlugin($alias)
    {
        if (! isset($this->plugins[$alias])) {
            throw new \RuntimeException("Plugin '$alias' is not registered.'");
        }

        return $this->bootstrapPlugin($alias);
    }


    /**
     * Boostrap the plugin.
     *
     * @param $alias
     *
     * @return mixed
     */
    protected function bootstrapPlugin($alias)
    {
        if (! isset($this->boostrapped[$alias])) {
            $this->boostrapped[$alias] = new $this->plugins[$alias];
        }

        return $this->boostrapped[$alias];
    }

}
