<?php

use org\bovigo\vfs\content\LargeFileContent;
use org\bovigo\vfs\vfsStream;
use SktT1Byungi\IntoOne\IntoOne;

class IntoOneTest extends PHPUnit_Framework_TestCase
{
    private $root;

    private $fpath;

    public function setUp()
    {
        $this->root = vfsStream::setup('root');

        $this->fpath = vfsStream::url('root/test');
    }

    public function testConcatAddData()
    {
        IntoOne::concat($this->fpath, function ($add) {
            $add->data('key1', 'aaa');
            $add->data('key2', 'bbb');
            $add->data('key3', 'ccc');
        });

        $this->assertTrue($this->root->hasChild('test'));

        $this->assertEquals('aaa', IntoOne::read($this->fpath, 'key1'));
        $this->assertEquals('bbb', IntoOne::read($this->fpath, 'key2'));
        $this->assertEquals('ccc', IntoOne::read($this->fpath, 'key3'));
    }

    public function testConcatAddPath()
    {
        $path1 = $this->tmpFilePath('bbb');
        $path2 = $this->tmpFilePath('ccc');

        IntoOne::concat($this->fpath, function ($add) use ($path1, $path2) {
            $add->data('key1', 'aaa');
            $add->path('key2', $path1);
            $add->path('key3', $path2);
        });

        $this->assertEquals('aaa', IntoOne::read($this->fpath, 'key1'));
        $this->assertEquals('bbb', IntoOne::read($this->fpath, 'key2'));
        $this->assertEquals('ccc', IntoOne::read($this->fpath, 'key3'));
    }

    public function testConcatAddResource()
    {
        $file1 = $this->tmpFile('bbb');
        $file2 = $this->tmpFile('ccc');

        IntoOne::concat($this->fpath, function ($add) use ($file1, $file2) {
            $add->data('key1', 'aaa');
            $add->resource('key2', $file1);
            $add->resource('key3', $file2);
        });

        $this->assertEquals('aaa', IntoOne::read($this->fpath, 'key1'));
        $this->assertEquals('bbb', IntoOne::read($this->fpath, 'key2'));
        $this->assertEquals('ccc', IntoOne::read($this->fpath, 'key3'));
    }

    private function tmpFilePath($content)
    {
        return vfsStream::newFile('tmpFile' . uniqid() . rand())
            ->setContent($content)
            ->at($this->root)
            ->url();
    }

    private function tmpFile($content)
    {
        return fopen($this->tmpFilePath($content), 'r');
    }

    public function testReadChunks()
    {
        $size = rand(1024 * 1024, 1024 * 1024 * 9);

        $path = $this->tmpFilePathBySize($size);

        IntoOne::concat($this->fpath, function ($add) use ($path) {
            $add->data('key1', 'aaa');
            $add->path('key2', $path);
            $add->data('key3', 'ccc');
        });

        $sum = 0;

        IntoOne::readChunks($this->fpath, 'key2', function ($chunk) use (&$sum) {
            $sum += strlen($chunk);
        });

        $this->assertEquals($size, $sum);
    }

    private function tmpFilePathBySize($size)
    {
        return vfsStream::newFile('large')
            ->withContent(new LargeFileContent($size))
            ->at($this->root)
            ->url();
    }

}
