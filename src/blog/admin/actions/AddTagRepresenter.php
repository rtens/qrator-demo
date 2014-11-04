<?php
namespace blog\admin\actions;

use blog\model\commands\AddTag;
use blog\model\PostRepository;
use watoki\qrator\representer\basic\BasicActionRepresenter;

class AddTagRepresenter extends BasicActionRepresenter {

    /** @var \watoki\qrator\RepresenterRegistry <- */
    protected $registry;

    /**
     * @param object $object of the action to be executed
     * @return mixed
     */
    public function execute($object) {
        return call_user_func($this->makeCallable(PostRepository::class), $object);
    }

    /**
     * @return string
     */
    public function getClass() {
        return AddTag::class;
    }
}