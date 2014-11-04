<?php
namespace blog\model\commands;

class UpdatePost {

    /** @var \blog\model\Post-ID|string */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    public function setId($id) {
        $this->id = $id;
    }

}