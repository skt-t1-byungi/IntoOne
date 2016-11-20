<?php

namespace SktT1Byungi\IntoOne;

use SktT1Byungi\IntoOne\Resource;

class Concat
{
    protected $resource;

    protected $total = 0;

    protected $files = [];

    protected $current;

    protected $size;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    public function start($name)
    {
        $this->current = $name;

        $this->size = 0;

        return $this;
    }

    public function end()
    {
        $this->files[$this->current] = [$this->total, $this->size]; //[offset, length]

        $this->total += $this->size;

        $this->current = null;

        $this->size = null;

        return $this;
    }

    public function write($data)
    {
        $size = strlen($data);

        $this->size += $size;

        $this->resource->fwrite($data);

        return $this;
    }

    public function finish()
    {
        $serialize = $this->serialize();

        $this->resource->fwrite($serialize);

        $size = strlen($serialize);

        $this->resource->fwrite(pack('V', $size)); //32bit fixed size
    }

    protected function serialize()
    {
        return serialize($this->files);
    }
}
