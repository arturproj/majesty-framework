<?php
namespace Spacers\Framework\Response;

class JsonResponse extends Response
{
    public function __construct(
        mixed $data,
        protected array $headers = [],
        protected int $code = 200
    ) {
        parent::__construct(json_encode($data), $headers, $code);
    }
}