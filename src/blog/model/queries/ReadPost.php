<?php
namespace blog\model\queries;

class ReadPost {

    /** @var string|\blog\model\Post-ID */
    public $id;

    public function setId($id) {
        $this->id = $id;
    }
}