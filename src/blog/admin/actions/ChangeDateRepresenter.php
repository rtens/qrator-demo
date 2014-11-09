<?php
namespace blog\admin\actions;

use blog\model\commands\ChangeDate;
use blog\model\PostRepository;
use watoki\qrator\representer\basic\BasicActionRepresenter;

class ChangeDateRepresenter extends BasicActionRepresenter {

    /**
     * @param object $object of the action to be executed
     * @return mixed
     */
    public function execute($object) {
        $this->executeHandler(PostRepository::class, $object);
    }

    /**
     * @return string
     */
    public function getClass() {
        return ChangeDate::class;
    }
}