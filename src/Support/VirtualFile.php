<?php

namespace Permafrost\PhpCodeSearch\Support;

class VirtualFile extends File
{
    public function __construct(string $code)
    {
        $this->createTempFile($code);

        $this->filename = $this->getRealPath();
    }

    public function __destruct()
    {
        $this->unlink();
    }

    public function unlink()
    {
        if (! strpos(dirname($this->path), sys_get_temp_dir())) {
            return;
        }

        if (is_file($this->path)) {
            unlink($this->path);
        }
    }

    protected function createTempFile(string $contents): bool
    {
        $this->path = tempnam(sys_get_temp_dir(), 'pcs');

        return file_put_contents($this->path, $contents) !== false;
    }
}
