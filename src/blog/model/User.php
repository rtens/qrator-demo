<?php
namespace blog\model;

class User {

    /** @var string|User-ID */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    function __construct($id, $email, $name) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
    }

    public function getId() {
        return $this->id;
    }
}