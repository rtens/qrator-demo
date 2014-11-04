<?php
namespace blog;

use blog\model\Blog;
use blog\model\commands\AddTag;
use blog\model\commands\CreatePost;
use blog\model\commands\CreateUser;
use blog\model\commands\DeletePost;
use blog\model\commands\DeleteUser;
use blog\model\commands\RemoveTag;
use blog\model\commands\UpdatePost;
use blog\model\Post;
use blog\model\PostRepository;
use blog\model\queries\ListPosts;
use blog\model\queries\ListTaggedPosts;
use blog\model\queries\ListUsers;
use blog\model\queries\ReadPost;
use blog\model\queries\ReadUser;
use blog\model\queries\ShowBlog;
use blog\model\Tag;
use blog\model\TagRepository;
use blog\model\User;
use blog\model\UserRepository;
use watoki\factory\Factory;
use watoki\qrator\form\fields\ArrayField;
use watoki\qrator\form\fields\SelectEntityField;
use watoki\qrator\representer\ActionGenerator;
use watoki\qrator\representer\GenericActionRepresenter;
use watoki\qrator\representer\GenericEntityRepresenter;
use watoki\qrator\representer\MethodActionRepresenter;
use watoki\qrator\representer\PropertyActionGenerator;
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

        $listTags = $this->deriveAction(TagRepository::class, 'listTags');

        $createTag = $this->deriveAction(TagRepository::class, 'createTag');

        $this->registerEntities([

            null => [
                [
                    ShowBlog::class
                ]],

            Blog::class => [
                [
                    ListPosts::class,
                    ListUsers::class,
                    $listTags->getClass(),
                    CreatePost::class,
                    CreateUser::class,
                    $createTag->getClass(),
                ]],


            Post::class => [
                [
                    ReadPost::class,
                    AddTag::class,
                    DeletePost::class,
                    UpdatePost::class,
                ],
                function (
                    GenericEntityRepresenter $representer) {
                    $representer->setStringifier(function (Post $post) {
                        return $post->title;
                    });
                    $representer->addPropertyAction('tags', new PropertyActionGenerator(RemoveTag::class, function ($id, $tagId) {
                        return [
                            'post' => $id,
                            'tag' => $tagId
                        ];
                    }));
                }],


            User::class => [
                [
                    ReadUser::class,
                    DeleteUser::class,
                ],
                function (GenericEntityRepresenter $user) {
                    $user->setStringifier(function (User $user) {
                        return $user->name;
                    });
                }],


            Tag::class => [
                [
                    ListTaggedPosts::class,
                ],
                function (GenericEntityRepresenter $tag) {
                }
            ],


            \DateTime::class => [
                [],
                function (GenericEntityRepresenter $representer) {
                    $representer->setStringifier(function (\DateTime $dateTime) {
                        return $dateTime->format('Y-m-d H:i:s');
                    });
                }
            ],
        ]);

        $this->registerActions([

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

            AddTag::class => [TagRepository::class,
                function (GenericActionRepresenter $remove) use ($listTags) {
                    $remove->setField('tag', new SelectEntityField('tag', $listTags->create(), $this->registry));
                }],

            CreatePost::class => [PostRepository::class,
                function (GenericActionRepresenter $createPost) use ($listTags) {

                    $createPost->setField('author',
                        new SelectEntityField('author', new ListUsers(), $this->registry));

                    $createPost->setField('tags',
                        new ArrayField('tags',
                            new SelectEntityField('tag', $listTags->create(), $this->registry)));

                    $createPost->setFollowUpAction(new ActionGenerator(ReadPost::class, function (Post $result) {
                        return ['id' => $result->id];
                    }));
                }],

            RemoveTag::class => [PostRepository::class,
                function (GenericActionRepresenter $remove)  use ($listTags) {
                    $remove->setField('tag', new SelectEntityField('tag', $listTags->create(), $this->registry));
                }],

            UpdatePost::class => [PostRepository::class,
                function (GenericActionRepresenter $representer) {
                    $representer->setPreFiller(function (UpdatePost $action) {
                        $readPost = new ReadPost();
                        $readPost->id = $action->id;

                        $post = $this->registry->getActionRepresenter($readPost)->execute($readPost);

                        $action->title = $post->title;
                        $action->content = $post->content;
                    });
                }],
        ]);
    }

    private function representEntity($class, $actions = [], $callback = null) {
        $representer = new GenericEntityRepresenter($class);
        foreach ($actions as $query) {
            $representer->addAction(new ActionGenerator($query));
        }
        if ($callback) {
            call_user_func($callback, $representer);
        }
        return $representer;
    }

    private function representAction($class, $handler, $callback = null) {
        $representer = new GenericActionRepresenter($class, $this->factory);
        $representer->setHandler($handler);
        if ($callback) {
            call_user_func($callback, $representer);
        }
        return $representer;
    }

    private function registerEntities($array) {
        foreach ($array as $class => $def) {
            $this->registry->register($this->representEntity($class, $def[0], isset($def[1]) ? $def[1] : null));
        }
    }

    private function registerActions($array) {
        foreach ($array as $class => $def) {
            if (!is_array($def)) {
                $def = [$def, null];
            }
            $this->registry->register($this->representAction($class, $def[0], $def[1]));
        }
    }

    private function deriveAction($handler, $method) {
        $action = new MethodActionRepresenter($handler, $method, $this->factory);
        $this->registry->register($action);
        return $action;
    }
}