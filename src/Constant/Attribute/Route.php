<?php

namespace Spacers\Framework\Constant\Attribute;

#[\Attribute]
class Route
{
    public function __construct(
        public string $path,
        public string $alias,
        public string $method
    ) {
    }
}