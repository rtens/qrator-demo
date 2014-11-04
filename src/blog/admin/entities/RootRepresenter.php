<?php
namespace blog\admin\entities;

use blog\model\queries\ShowBlog;
use watoki\qrator\representer\basic\BasicEntityRepresenter;

class RootRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return null;
    }

    public function getActions() {
        return $this->wrapInActionGenerators([
            ShowBlog::class
        ]);
    }
}