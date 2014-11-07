<?php
namespace blog\admin\actions;

use blog\model\commands\CreatePost;
use blog\model\Post;
use blog\model\PostRepository;
use blog\model\queries\ReadPost;
use watoki\qrator\form\fields\HtmlEditorField;
use watoki\qrator\representer\ActionLink;
use watoki\qrator\representer\basic\BasicActionRepresenter;
use watoki\qrator\representer\Property;

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

    /**
     * @param Post $result
     * @return ActionLink
     */
    public function getFollowUpAction($result) {
        return new ActionLink(ReadPost::class, ['id' => $result->id]);
    }

    /**
     * @return string
     */
    public function getClass() {
        return CreatePost::class;
    }

    protected function getField(Property $property) {
        switch ($property->name()) {
            case 'content':
                return new HtmlEditorField('content');
            default:
                return parent::getField($property);
        }
    }
}