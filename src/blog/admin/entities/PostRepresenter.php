<?php
namespace blog\admin\entities;

use blog\model\commands\AddTag;
use blog\model\commands\DeletePost;
use blog\model\commands\RemoveTag;
use blog\model\commands\UpdatePost;
use blog\model\Post;
use blog\model\queries\ReadPost;
use watoki\qrator\representer\basic\BasicEntityRepresenter;
use watoki\qrator\representer\PropertyActionGenerator;

class PostRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return Post::class;
    }

    /**
     * @return array|\watoki\qrator\representer\ActionGenerator[]
     */
    public function getActions() {
        return $this->wrapInActionGenerators([
            ReadPost::class,
            AddTag::class,
            DeletePost::class,
            UpdatePost::class,
        ]);
    }

    /**
     * @param string $property
     * @return array|\watoki\qrator\representer\ActionGenerator[]
     */
    public function getPropertyActions($property) {
        switch ($property) {
            case 'tags':
                return [
                    new PropertyActionGenerator(RemoveTag::class, function ($id, $tagId) {
                        return [
                            'post' => $id,
                            'tag' => $tagId
                        ];
                    })
                ];
            default:
                return [];
        }
    }

    /**
     * @param null|Post $object
     * @return string
     */
    public function toString($object) {
        return $object->title;
    }

}