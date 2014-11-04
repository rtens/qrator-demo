<?php
namespace blog\admin\entities;

use blog\model\Blog;
use blog\model\commands\CreatePost;
use blog\model\commands\CreateUser;
use blog\model\queries\ListPosts;
use blog\model\queries\ListUsers;
use blog\model\TagRepository;
use watoki\qrator\representer\basic\BasicEntityRepresenter;
use watoki\qrator\representer\MethodActionRepresenter;

class BlogRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return Blog::class;
    }

    public function getActions() {
        return $this->wrapInActionGenerators([
            ListPosts::class,
            ListUsers::class,
            MethodActionRepresenter::asClass(TagRepository::class, 'listTags'),
            CreatePost::class,
            CreateUser::class,
            MethodActionRepresenter::asClass(TagRepository::class, 'createTag'),
        ]);
    }
}