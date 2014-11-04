<?php
namespace blog\model\commands;

class UpdatePost {

    /** @var string|\blog\model\Post-ID */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    public function setId($id) {
        $this->id = $id;
    }

}