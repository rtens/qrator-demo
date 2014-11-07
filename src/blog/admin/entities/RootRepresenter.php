<?php
namespace blog\admin\entities;

use blog\model\queries\ShowBlog;
use watoki\qrator\representer\ActionLink;
use watoki\qrator\representer\basic\BasicEntityRepresenter;
use watoki\qrator\RootEntity;

class RootRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return RootEntity::class;
    }

    public function toString($o) {
        return 'Welcome to Qrator';
    }

    public function getActions($entity) {
        return [
            new ActionLink(ShowBlog::class)
        ];
    }
}