<?php

use org\bovigo\vfs\vfsStream;
use SktT1Byungi\IntoOne\Resource;

class ResourceTest extends PHPUnit_Framework_TestCase
{
    private $root;

    private $content;

    private $url;

    private $resource;

    public function setUp()
    {
        $this->root = vfsStream::setup('root');

        $this->content = uniqid() . rand() . time();

        $this->url = vfsStream::newFile('tmpFile')
            ->setContent($this->content)
            ->at($this->root)
            ->url();
    }

    public function modes()
    {
        //[mode, writable, readable]
        return [
            ['r', false, true],
            ['w', true, false],
            ['a', true, false],
            // ['x', true, false],
            ['c', true, false],
            ['r+', true, true],
            ['w+', true, true],
            ['a+', true, true],
            // ['x+', true, true],
            ['c+', true, true],
        ];
    }

    /**
     * @dataProvider modes
     */
    public function testReadable($mode, $writable, $readable)
    {
        $resource = Resource::fopen($this->url, $mode);
        $this->assertEquals($writable, $resource->isWritable());
        $this->assertEquals($readable, $resource->isReadable());
    }

    /**
     * @dataProvider modes
     */
    public function testWritable($mode, $writable, $readable)
    {
        $resource = Resource::fopen($this->url, $mode);
        $this->assertEquals($writable, $resource->isWritable());
        $this->assertEquals($readable, $resource->isReadable());
    }
}
