<?php

require '../vendor/autoload.php';

$path = "test.bin";

$data = SktT1Byungi\IntoOne\IntoOne::read($path, 'key1'); // $data == 'abcd'

var_dump($data); //true;
