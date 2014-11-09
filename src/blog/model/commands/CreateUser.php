<?php
namespace blog\model\commands;

class CreateUser {

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var boolean */
    public $showEmail = true;

    function __construct($name, $email = null) {
        $this->email = $email;
        $this->name = $name;
    }

}