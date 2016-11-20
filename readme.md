SktT1Byungi/IntoOne
==============================
merge files into one

Description
---
여러 파일을 merge해서 관리하기 위한 용도.

Usage
---

```php
use SktT1Byungi\IntoOne;

$path = "test.bin";

var_dump(is_file($path)); //false;

IntoOne::concat($path, function ($add) {
    $add->data('key1', 'abcd');
    $add->path('key2', 'files/test.txt');
    $add->resource('key3', fopen('php://stdin', 'r'));
});

var_dump(is_file($path)); //true;

$data = IntoOne::read($path, 'key1'); // $data == 'abcd'

//for large file
$content = '';
IntoOne::readChunks($path, "key2", function ($chunk) use ($content) {
    $content .= $chunk;
});

//$content == file_get_contents("files/test.txt")


```



