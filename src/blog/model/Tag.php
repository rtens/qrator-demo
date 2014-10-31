<?php
namespace blog\model;

class Tag {

    public $id;

    function __construct($name) {
        $this->id = $name;
    }

} 