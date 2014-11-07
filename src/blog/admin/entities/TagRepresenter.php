<?php
namespace blog\admin\entities;

use blog\model\queries\ListTaggedPosts;
use blog\model\Tag;
use blog\model\TagRepository;
use watoki\qrator\representer\ActionLink;
use watoki\qrator\representer\basic\BasicEntityRepresenter;
use watoki\qrator\representer\MethodActionRepresenter;

class TagRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return Tag::class;
    }

    public function getActions($entity) {
        return [
            new ActionLink(ListTaggedPosts::class),
        ];
    }

    public function getListAction() {
        return new ActionLink(MethodActionRepresenter::asClass(TagRepository::class, 'listTags'));
    }
}