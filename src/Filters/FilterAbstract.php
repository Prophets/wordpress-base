<?php

namespace Prophets\WPBase\Filters;

use Prophets\WPBase\Base;

class FilterAbstract
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
