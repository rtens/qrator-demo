<?php
namespace blog\admin\entities;

use blog\model\commands\AddTag;
use blog\model\commands\DeletePost;
use blog\model\commands\RemoveTag;
use blog\model\commands\UpdatePost;
use blog\model\Post;
use blog\model\queries\ListPosts;
use blog\model\queries\ReadPost;
use watoki\qrator\representer\ActionLink;
use watoki\qrator\representer\basic\BasicEntityRepresenter;

class PostRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return Post::class;
    }

    /**
     * @param Post $post
     * @return array|\object[]
     */
    public function getActions($post) {
        $args = ['id' => $post->id];

        return [
            new ActionLink(ReadPost::class, $args),
            new ActionLink(AddTag::class, $args),
            new ActionLink(DeletePost::class, $args),
            new ActionLink(UpdatePost::class, $args),
        ];
    }

    /**
     * @param object $entity
     * @param string $property
     * @param mixed|\blog\model\Tag $value
     * @return array|\watoki\qrator\representer\ActionLink[]
     */
    public function getPropertyActions($entity, $property, $value) {
        switch ($property) {
            case 'tags':
                return [
                    new ActionLink(RemoveTag::class, [
                        'post' => $entity->id,
                        'tag' => $value->id
                    ])
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

    public function getListAction() {
        return new ActionLink(ListPosts::class);
    }

}