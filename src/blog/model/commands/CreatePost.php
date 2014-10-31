<?php
namespace blog\model\commands;

class CreatePost {

    /** @var string|\blog\model\User-ID */
    public $author;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var array|\blog\model\Tag-ID[] */
    public $tags;

    function __construct($author, $title, $content) {
        $this->author = $author;
        $this->content = $content;
        $this->title = $title;
        $this->tags = [];
    }

}