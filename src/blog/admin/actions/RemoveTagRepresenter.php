<?php
namespace blog\admin\actions;

use blog\model\commands\RemoveTag;
use blog\model\PostRepository;
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

    /**
     * @return string
     */
    public function getClass() {
        return RemoveTag::class;
    }
}