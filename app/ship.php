<?php

namespace App;

class Ship
{
    public $name;
    public $length;

    public function __construct($name, $length)
    {
        $this->name = $name;
        $this->length = $length;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLength()
    {
        return $this->length;
    }
}
