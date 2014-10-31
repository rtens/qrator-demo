<?php
namespace blog\model\commands;

use blog\curator\contracts\Command;
use blog\curator\contracts\EntityAction;
use blog\curator\Curator;
use blog\curator\contracts\Preparable;
use blog\model\Post;
use blog\model\queries\ReadPost;
use watoki\cqurator\form\PreFilling;
use watoki\smokey\Dispatcher;

class UpdatePost implements PreFilling {

    /** @var string|\blog\model\Post-ID */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param Dispatcher $dispatcher
     * @return void
     */
    public function preFill(Dispatcher $dispatcher) {
        $readPost = new ReadPost();
        $readPost->id = $this->id;
        $dispatcher->fire($readPost)->onSuccess(function (Post $post) {
            $this->title = $post->title;
            $this->content = $post->content;
        });
    }
}