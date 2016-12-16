<?php

namespace SktT1Byungi\IntoOne;

use InvalidArgumentException;
use RuntimeException;

class Resource
{
    const CHUNK_SIZE = 8192;

    protected $resource;

    protected $stat;

    protected $mode;

    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException("not resource!");
        }

        $this->resource = $resource;

        $this->stat = fstat($resource);

        $this->mode = $this->getMeta('mode');
    }

    protected function getMeta($name = null)
    {
        $meta = stream_get_meta_data($this->resource);

        if (is_null($name)) {
            return $meta;
        }

        return $meta[$name];
    }

    public function __destruct()
    {
        // fclose($this->resource);
    }

    public function isWritable()
    {
        return preg_match('/[+waxc]/', $this->mode) > 0;
    }

    public function isReadable()
    {
        return preg_match('/[+r]/', $this->mode) > 0;
    }

    public function checkWritable()
    {
        if (!$this->isWritable()) {
            throw new RuntimeException("not writable resource!");
        }
    }

    public function checkReadable()
    {
        if (!$this->isReadable()) {
            throw new RuntimeException("not readable resource!");
        }
    }

    public static function fopen($path, $mode)
    {
        return new static(fopen($path, $mode));
    }

    public function fwrite($data)
    {
        $this->checkWritable();

        return fwrite($this->resource, $data);
    }

    public function chunks(callable $callable)
    {
        $this->checkReadable();

        $this->fseek(0);

        while (!feof($this->resource)) {
            $chunk = fread($this->resource, static::CHUNK_SIZE);

            $callable($chunk);
        }
    }

    public function fseek($offset, $whence = SEEK_SET)
    {
        fseek($this->resource, $offset, $whence);
    }

    public function partial($offset, $length = null)
    {
        $this->checkReadable();

        if (is_null($length)) {
            $length = $this->size();
        }

        $whence = $offset >= 0 ? SEEK_SET : SEEK_END;
        $this->fseek($offset, $whence);

        return fread($this->resource, $length);
    }

    public function size()
    {
        return $this->stat['size'];
    }

    public function partialChunks($offset, $length, callable $callable)
    {
        $this->checkReadable();

        $readable = $length;

        $whence = $offset >= 0 ? SEEK_SET : SEEK_END;
        $this->fseek($offset);

        while (!feof($this->resource) && $readable > 0) {
            $size = $readable > static::CHUNK_SIZE ? static::CHUNK_SIZE : $readable;

            $chunk = fread($this->resource, $size);

            $callable($chunk);

            $readable -= $size;
        }
    }
}
