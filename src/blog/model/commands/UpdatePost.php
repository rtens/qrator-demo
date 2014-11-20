<?php
namespace blog\model\commands;

class UpdatePost {

    /** @var \blog\model\Post-ID|string */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    function __construct($id, $title, $content) {
        $this->content = $content;
        $this->id = $id;
        $this->title = $title;
    }

    public function setId($id) {
        $this->id = $id;
    }

}