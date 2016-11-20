<?php

namespace SktT1Byungi\IntoOne;

use SktT1Byungi\IntoOne\Add;
use SktT1Byungi\IntoOne\Concat;
use SktT1Byungi\IntoOne\Resource;
use SktT1Byungi\IntoOne\Seek;

class IntoOne
{
    public static function concat($path, callable $callable = null)
    {
        $resource = Resource::fopen($path, 'w');

        $concat = new Concat($resource);

        $add = new Add($concat);

        $callable($add);

        $concat->finish();
    }

    public static function read($path, $name)
    {
        $resource = Resource::fopen($path, 'r');

        $seek = new Seek($resource);

        return $seek->read($name);
    }

    public static function raadChunks($path, $name, callable $callable)
    {
        $resource = Resource::fopen($path, 'r');

        $seek = new Seek($resource);

        $seek->readChunks($name, $callable);
    }
}
