<?php
namespace blog\model\commands;

class DeleteUser {

    /** @var \blog\model\User-ID|string */
    public $id;

    public function setId($id) {
        $this->id = $id;
    }
}