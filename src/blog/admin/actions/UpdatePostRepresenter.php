<?php
namespace blog\admin\actions;

use blog\model\commands\UpdatePost;
use blog\model\PostRepository;
use blog\model\queries\ReadPost;
use watoki\qrator\representer\basic\BasicActionRepresenter;

class UpdatePostRepresenter extends BasicActionRepresenter {

    /** @var \watoki\qrator\RepresenterRegistry <- */
    protected $registry;

    /**
     * @param object $object of the action to be executed
     * @return mixed
     */
    public function execute($object) {
        return $this->executeHandler(PostRepository::class, $object);
    }

    /**
     * @return string
     */
    public function getClass() {
        return UpdatePost::class;
    }

    public function preFill($action) {
        $readPost = new ReadPost();
        $readPost->id = $action->id;

        $post = $this->registry->getActionRepresenter($readPost)->execute($readPost);

        $action->title = $post->title;
        $action->content = $post->content;
    }
}