<?php

require '../vendor/autoload.php';

$path = "test.bin";

var_dump(is_file($path)); //false;

SktT1Byungi\IntoOne\IntoOne::concat($path, function ($add) {
    $add->data('key1', 'aaa');
    $add->data('key2', 'bbb');
    $add->data('key3', 'ccc');
});

var_dump(is_file($path)); //true;
