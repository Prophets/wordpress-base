<?php

namespace Prophets\WPBase\Actions;

use Prophets\WPBase\PluginRepository;

class ActionAbstract
{
    /**
     * @var PluginRepository
     */
    protected $pluginRepository;

    public function __construct(PluginRepository $pluginRepository)
    {
        $this->pluginRepository = $pluginRepository;
    }
}
