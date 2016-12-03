<?php

namespace App;

class Ship
{
    public $name;
    public $length;

    protected $hits;

    public function __construct($name, $length)
    {
        $this->name = $name;
        $this->length = $length;

        $this->hits = [];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function shot($position)
    {
        $this->hits[] = $position;
        $this->hits = array_unique($this->hits);

        return $this->sunken();
    }

    public function sunken()
    {
        return count($this->hits) == $this->length;
    }
}
