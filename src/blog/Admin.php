<?php
namespace blog;

use blog\admin\actions\AddTagRepresenter;
use blog\admin\actions\CreatePostRepresenter;
use blog\admin\actions\RemoveTagRepresenter;
use blog\admin\actions\UpdatePostRepresenter;
use blog\admin\entities\BlogRepresenter;
use blog\admin\entities\DateTimeRepresenter;
use blog\admin\entities\PostRepresenter;
use blog\admin\entities\RootRepresenter;
use blog\admin\entities\TagRepresenter;
use blog\admin\entities\UserRepresenter;
use blog\model\Blog;
use blog\model\commands\CreateUser;
use blog\model\commands\DeletePost;
use blog\model\commands\DeleteUser;
use blog\model\PostRepository;
use blog\model\queries\ListPosts;
use blog\model\queries\ListTaggedPosts;
use blog\model\queries\ListUsers;
use blog\model\queries\ReadPost;
use blog\model\queries\ReadUser;
use blog\model\queries\ShowBlog;
use blog\model\TagRepository;
use blog\model\UserRepository;
use watoki\factory\Factory;
use watoki\qrator\representer\generic\GenericActionRepresenter;
use watoki\qrator\representer\MethodActionRepresenter;
use watoki\qrator\RepresenterRegistry;

class Admin {

    /** @var \watoki\qrator\RepresenterRegistry */
    private $registry;

    /** @var \watoki\factory\Factory */
    private $factory;

    public static function init(Factory $factory) {
        new Admin($factory, new RepresenterRegistry($factory));
    }

    function __construct(Factory $factory, RepresenterRegistry $registry) {
        $this->factory = $factory;
        $this->registry = $registry;

        $factory->setSingleton(get_class($registry), $registry);

        $this->registerRepresenters();
    }

    protected function registerRepresenters() {

        $this->registry->register(new MethodActionRepresenter(TagRepository::class, 'createTag', $this->factory, $this->registry));
        $this->registry->register(new MethodActionRepresenter(TagRepository::class, 'listTags', $this->factory, $this->registry));

        $this->register(PostRepresenter::class);
        $this->register(RootRepresenter::class);
        $this->register(BlogRepresenter::class);
        $this->register(UserRepresenter::class);
        $this->register(TagRepresenter::class);
        $this->register(DateTimeRepresenter::class);

        $this->register(AddTagRepresenter::class);
        $this->register(CreatePostRepresenter::class);
        $this->register(RemoveTagRepresenter::class);
        $this->register(UpdatePostRepresenter::class);

        $this->registerActionHandlers([
            ShowBlog::class => function () {
                    return new Blog();
                },
            DeletePost::class => PostRepository::class,
            ListPosts::class => PostRepository::class,
            ListTaggedPosts::class => PostRepository::class,
            ReadPost::class => PostRepository::class,
            CreateUser::class => UserRepository::class,
            DeleteUser::class => UserRepository::class,
            ListUsers::class => UserRepository::class,
            ReadUser::class => UserRepository::class,
        ]);
    }

    private function registerActionHandlers($array) {
        foreach ($array as $class => $handler) {
            $representer = new GenericActionRepresenter($class, $this->factory, $this->registry);
            $representer->setHandler($handler);

            $this->registry->register($representer);
        }
    }

    private function register($class) {
        $this->registry->register($this->factory->getInstance($class));
    }
}