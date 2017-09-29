<?php

namespace Logging\Model;

use PHPUnit\Framework\TestCase;

class FluentModelTest extends TestCase
{
    public $fluent;
    public $module;

    public function setUp()
    {
        $this->fluent = new Fluent();
        parent::setUp();
    }

    public function testFluent()
    {
        $this->assertEquals('gibdd-td_agent', $this->fluent->host);
        $this->assertEquals('24224', $this->fluent->port);
        $this->assertEquals(array(), $this->fluent->options);
    }
}