<?php

namespace SktT1Byungi\IntoOne;

use SktT1Byungi\IntoOne\Concat;
use SktT1Byungi\IntoOne\Resource;

class Add
{
    protected $concat;

    public function __construct(Concat $concat)
    {
        $this->concat = $concat;
    }

    public function data($name, $data)
    {
        $this->concat
            ->start($name)
            ->write($data)
            ->end();

        return $this;
    }

    public function path($name, $path)
    {
        $file = Resource::fopen($path, 'r');

        $this->writeChunks($name, $file);

        return $this;
    }

    public function resource($name, $resource)
    {
        $file = new Resource($resource);

        $this->writeChunks($name, $file);

        return $this;
    }

    protected function writeChunks($name, Resource $file)
    {
        $concat = $this->concat;

        $concat->start($name);

        $file->chunks(function ($chunk) use ($cocat) {
            $concat->write($chunk);
        });

        $concat->end();
    }
}
