<?php

namespace SktT1Byungi\IntoOne;

use SktT1Byungi\IntoOne\Resource;

class Seek
{
    protected $resource;

    protected $files;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;

        $this->files = $this->unserialize();
    }

    protected function unserialize()
    {
        $bin = $this->resource->partial(-4); //4byte == 32bit

        $length = unpack('V', $bin)[1];

        $offset = $this->resource->size() - ($length + 4);

        return unserialize($this->resource->partial($offset, $length));
    }

    public function read($name)
    {
        list($offset, $length) = $this->getFile($name);

        return $this->resource->partial($offset, $length);
    }

    public function readChunks($name, callable $callable)
    {
        list($offset, $length) = $this->getFile($name);

        $this->resource->partialChunks($offset, $length, $callable);
    }

    protected function getFile($name)
    {
        return $this->files[$name];
    }
}
