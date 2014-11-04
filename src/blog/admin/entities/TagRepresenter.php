<?php
namespace blog\admin\entities;

use blog\model\queries\ListTaggedPosts;
use blog\model\Tag;
use blog\model\TagRepository;
use watoki\qrator\representer\ActionGenerator;
use watoki\qrator\representer\basic\BasicEntityRepresenter;
use watoki\qrator\representer\MethodActionRepresenter;

class TagRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return Tag::class;
    }

    public function getActions() {
        return $this->wrapInActionGenerators([
            ListTaggedPosts::class,
        ]);
    }

    public function getListAction() {
        return new ActionGenerator(MethodActionRepresenter::asClass(TagRepository::class, 'listTags'));
    }
}