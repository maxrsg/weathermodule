<?php

namespace Anax\Proxy;

/**
 * A mock service to add to the .
 */
class MockService
{
    public function sayHi() : string
    {
        return "Hi";
    }



    public function getInstance() : object
    {
        return $this;
    }
}
