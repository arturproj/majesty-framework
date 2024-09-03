<?php
namespace Spacers\Framework\Request;

use \Spacers\Framework\Constant\Attribute\Route;

class Request
{
    protected Route $route;
    protected string $content;
    protected array $headers; 
    public function __construct(
    ) {
        try {
            $this->route = new Route(
                path: $_SERVER["REQUEST_URI"],
                alias: "client_current_route",
                method: $_SERVER["REQUEST_METHOD"]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
        try {
            $this->content = file_get_contents("php://input");
        } catch (\Throwable $th) {
            $this->content = "";
        }
        try {
            $this->headers = apache_request_headers();
        } catch (\Throwable $th) {
            $this->headers = [];
        }
    }
    public function getContent(): string
    {
        return $this->content;
    }

    public function getMethod(): string
    {
        return $this->route->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function toArray(): array
    {
        if (json_validate($this->content)) {
            return json_decode($this->content, true);
        }

        return [];
    }
}