<?php

IntoOne::concat($path, function ($add) {
    $add->data($key, $data);
    $add->path($key, $path);
    $add->resource($key, $resource);
});

$data = IntoOne::read($path, $name);

IntoOne::readChunks($path, $name, function ($chunk) {

});
