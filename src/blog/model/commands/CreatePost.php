<?php
namespace blog\model\commands;

use blog\model\UserId;

class CreatePost {

    /** @var \blog\model\UserId */
    public $author;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var array|string[]|\blog\model\Tag-ID[] */
    public $tags;

    function __construct(UserId $author, $title, $content) {
        $this->author = $author;
        $this->content = $content;
        $this->title = $title;
        $this->tags = [];
    }

}