<?php
namespace blog\admin\actions;

use blog\model\commands\RemoveTag;
use blog\model\PostRepository;
use blog\model\Tag;
use watoki\qrator\form\fields\SelectEntityField;
use watoki\qrator\representer\basic\BasicActionRepresenter;

class RemoveTagRepresenter extends BasicActionRepresenter {

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
            case 'tag':
                return new SelectEntityField('tag', Tag::class, $this->registry);
        }
        return parent::getField($name);
    }

    /**
     * @return string
     */
    public function getClass() {
        return RemoveTag::class;
    }
}