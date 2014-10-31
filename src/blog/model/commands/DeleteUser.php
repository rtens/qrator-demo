<?php
namespace blog\model\commands;

class DeleteUser {

    /** @var string|\blog\model\User-ID */
    public $id;

    public function setId($id) {
        $this->id = $id;
    }
}