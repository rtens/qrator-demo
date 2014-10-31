<?php
namespace blog\model;

class Post {

    /** @var string */
    public $id;

    /** @var string|User-ID */
    public $author;

    /** @var \DateTime */
    public $date;

    /** @var \DateTime */
    public $updated;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var array|Tag[] */
    public $tags = [];

    function __construct($id, User $author, $title, $content, $date, $updated) {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
        $this->title = $title;
        $this->date = $date;
        $this->updated = $updated;
    }

    public function getId() {
        return $this->id;
    }

} 