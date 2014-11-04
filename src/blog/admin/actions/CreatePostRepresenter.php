<?php
namespace blog\admin\actions;

use blog\model\commands\CreatePost;
use blog\model\Post;
use blog\model\PostRepository;
use blog\model\queries\ReadPost;
use blog\model\Tag;
use blog\model\User;
use watoki\qrator\form\fields\ArrayField;
use watoki\qrator\form\fields\SelectEntityField;
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

    public function getField($name) {
        switch ($name) {
            case 'tags':
                return new ArrayField('tags',
                    new SelectEntityField('tag', Tag::class, $this->registry));
            case 'author':
                return new SelectEntityField('author', User::class, $this->registry);
            default:
                return parent::getField($name);
        }
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