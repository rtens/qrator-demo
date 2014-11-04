<?php
namespace blog\admin\actions;

use blog\model\commands\CreatePost;
use blog\model\Post;
use blog\model\PostRepository;
use blog\model\queries\ReadPost;
use watoki\qrator\representer\ActionGenerator;
use watoki\qrator\representer\basic\BasicActionRepresenter;

class CreatePostRepresenter extends BasicActionRepresenter {

    /** @var \watoki\qrator\RepresenterRegistry <- */
    protected $registry;

    /**
     * @param object $object of the action to be executed
     * @return mixed
     */
    public function execute($object) {
        return $this->executeHandler(PostRepository::class, $object);
    }

    public function getFollowUpAction() {
        return new ActionGenerator(ReadPost::class, function (Post $result) {
            return ['id' => $result->id];
        });
    }

    /**
     * @return string
     */
    public function getClass() {
        return CreatePost::class;
    }
}