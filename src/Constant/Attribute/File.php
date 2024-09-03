<?php

namespace Spacers\Framework\Constant\Attribute;
use Spacers\Framework\Exception\NotFoundExcetion;

class File
{
    protected string $mimetype;
    protected array $path;
    public function __construct(
        protected string $filename
    ) {
        if (!file_exists($this->filename)) {
            throw new NotFoundExcetion("File source '{$this->filename}' not found");
        }
        $this->mimetype = mime_content_type($this->filename);
        $this->path = pathinfo($this->filename);

    }
    public function getFilename(): string
    {
        return $this->filename;
    }
    public function getMimetype(): string
    {
        return $this->mimetype;
    }
    public function getContent(): string
    {
        return file_get_contents($this->filename);
    }

    public function getPath(): array
    {
        return $this->path;
    }
}