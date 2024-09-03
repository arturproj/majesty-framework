<?php

namespace Spacers\Framework\Response;
use Spacers\Framework\Constant\Attribute\HeaderType;

class Response
{
    public function __construct(
        protected string $content,
        protected array $headers = [],
        protected int $code = 200,
    ) {
        /**
         * Config headers record
         * @var array $headers
         */
        $fi = new \finfo(FILEINFO_MIME_TYPE);
        $this->headers[] = new HeaderType(name: "content-type", value: $fi->buffer($this->content) . "; charset=utf-8");

        $this->headers[] = new HeaderType(name: "x-powered-by", value: "Spacers Framework PHP/" . PHP_VERSION);
    }

    private function find_header_content_type(string $key, array $headers): ?HeaderType
    {
        return array_reduce($headers, function ($res, $item) use ($key) {
            return $item->name === $key;
        }, null);
    }

    public function __get($key)
    {
        return $this->$key;
    }
}