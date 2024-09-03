<?php

namespace Spacers\Framework\Response;
use Spacers\Framework\Constant\Attribute\File;
use Spacers\Framework\Constant\Attribute\HeaderType;

class FileResponse extends Response
{
    public function __construct(
        File|string $file,
        array $proprieties = [],
        protected array $headers = [],
        protected int $code = 200
    ) {
        if (!$file instanceof File) {
            $file = new File($file);
        }
        parent::__construct(render_template($file->getFilename(), $proprieties), $headers, $code);
    }
}