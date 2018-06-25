<?php

namespace Prophets\WPBase\Actions;

use Prophets\WPBase\Base;

class ActionAbstract
{
    /**
     * @var Base
     */
    protected $base;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }
}
