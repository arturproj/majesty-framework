<?php

namespace Spacers\Framework\Constant\Attribute;

#[\Attribute]
class Route
{
    public string $path;
    public string $alias;
    public string $method;

    public function __construct(
        string $path,
        string $alias,
        string $method
    ) {
        $this->path = $path;
        $this->alias = $alias;
        $this->method = $method;
    }
}