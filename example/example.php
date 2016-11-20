<?php
$path = "test.bin";

IntoOne::concat($path, function ($add) {
    $add->data('1', 'abcd');
    $add->path('2', 'files/test.txt');
    $add->resource('3', fopen('php://stdin', 'r'));
});

$data = IntoOne::read($path, '1'); // $data == 'abcd'

$content = '';
IntoOne::readChunks($path, 2, function ($chunk) use ($content) {
    $content .= $chunk;
});
//$content == file_get_contents(files/test.txt)
