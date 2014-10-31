<?php
namespace blog\model\commands;

class DeletePost {

    /** @var string|\blog\model\Post-ID */
    public $id;

    public function setId($id) {
        $this->id = $id;
    }

}