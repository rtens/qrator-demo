<?php
namespace blog\model\commands;

class AddTag {

    /** @var \blog\model\Post-ID|string */
    public $id;

    /** @var \blog\model\Tag-ID|string */
    public $tag;

    function __construct($id, $tag) {
        $this->id = $id;
        $this->tag = $tag;
    }

} 