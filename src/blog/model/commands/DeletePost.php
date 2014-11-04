<?php
namespace blog\model\commands;

class DeletePost {

    /** @var \blog\model\Post-ID|string */
    public $id;

    public function setId($id) {
        $this->id = $id;
    }

}