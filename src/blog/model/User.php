<?php
namespace blog\model;

class User {

    /** @var UserId */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    function __construct(UserId $id, $email, $name) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }

    public function isEmailVisible() {
        return true;
    }
}