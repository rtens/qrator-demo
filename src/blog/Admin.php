<?php
namespace blog;

use blog\model\Blog;
use blog\model\commands\AddTag;
use blog\model\commands\CreatePost;
use blog\model\commands\CreateTag;
use blog\model\commands\CreateUser;
use blog\model\commands\DeletePost;
use blog\model\commands\DeleteUser;
use blog\model\commands\RemoveTag;
use blog\model\commands\UpdatePost;
use blog\model\Post;
use blog\model\PostRepository;
use blog\model\queries\ListPosts;
use blog\model\queries\ListTaggedPosts;
use blog\model\queries\ListTags;
use blog\model\queries\ListUsers;
use blog\model\queries\ReadPost;
use blog\model\queries\ReadUser;
use blog\model\queries\ShowBlog;
use blog\model\Tag;
use blog\model\TagRepository;
use blog\model\User;
use blog\model\UserRepository;
use watoki\qrator\ActionDispatcher;
use watoki\qrator\form\fields\ArrayField;
use watoki\qrator\form\fields\SelectEntityField;
use watoki\qrator\representer\ActionGenerator;
use watoki\qrator\representer\GenericActionRepresenter;
use watoki\qrator\representer\GenericEntityRepresenter;
use watoki\qrator\representer\PropertyActionGenerator;
use watoki\qrator\RepresenterRegistry;
use watoki\factory\Factory;

class Admin {

    /**
     * @var \watoki\qrator\RepresenterRegistry
     */
    private $registry;
    /**
     * @var \watoki\qrator\ActionDispatcher
     */
    private $dispatcher;

    /** @var \watoki\factory\Factory */
    private $factory;

    public static function init(Factory $factory) {
        new Admin($factory, new RepresenterRegistry($factory), new ActionDispatcher($factory));
    }

    function __construct(Factory $factory, RepresenterRegistry $registry, ActionDispatcher $dispatcher) {
        $this->factory = $factory;
        $this->registry = $registry;
        $this->dispatcher = $dispatcher;

        $factory->setSingleton(get_class($registry), $registry);
        $factory->setSingleton(get_class($dispatcher), $dispatcher);

        $this->register($this->representers());
    }

    protected function representers() {

        $userRepresenter = $this->representEntity(
            [
                ReadUser::class => UserRepository::class
            ],
            [
                DeleteUser::class => UserRepository::class,
            ],
            function (GenericEntityRepresenter $user) {
                $user->setStringifier(function (User $user) {
                    return $user->name;
                });
            });

        $tagRepresenter = $this->representEntity(
            [
                ListTaggedPosts::class => PostRepository::class
            ],
            [],
            function (GenericEntityRepresenter $tag) {
            }
        );

        return [

            null => $this->representEntity(
                    [
                        ShowBlog::class => function () {
                                return new Blog();
                            }
                    ]),

            Blog::class => $this->representEntity(
                    [
                        ListPosts::class => PostRepository::class,
                        ListUsers::class => UserRepository::class,
                        ListTags::class => TagRepository::class
                    ],
                    [
                        CreatePost::class => PostRepository::class,
                        CreateUser::class => UserRepository::class,
                        CreateTag::class => TagRepository::class
                    ]),

            Post::class => $this->representEntity(
                    [
                        ReadPost::class => PostRepository::class
                    ],
                    [
                        AddTag::class => PostRepository::class,
                        DeletePost::class => PostRepository::class,
                        UpdatePost::class => PostRepository::class
                    ],
                    function (GenericEntityRepresenter $representer) {
                        $representer->setStringifier(function (Post $post) {
                            return $post->title;
                        });
                        $this->dispatcher->addActionHandler(RemoveTag::class, PostRepository::class);
                        $representer->addPropertyAction('tags', new PropertyActionGenerator(RemoveTag::class, function ($id, $tagId) {
                            return [
                                'post' => $id,
                                'tag' => $tagId
                            ];
                        }));
                    }),

            User::class => $userRepresenter,

            Tag::class => $tagRepresenter,

            CreatePost::class => $this->representAction(
                    function (GenericActionRepresenter $createPost) use ($userRepresenter, $tagRepresenter) {

                        $createPost->setField('author',
                            new SelectEntityField('author', new ListUsers(), $userRepresenter, $this->dispatcher));

                        $createPost->setField('tags',
                            new ArrayField('tags',
                                new SelectEntityField('tag', new ListTags(), $tagRepresenter, $this->dispatcher)));
                    }),

            RemoveTag::class => $this->representAction(
                    function (GenericActionRepresenter $remove) use ($tagRepresenter) {
                        $remove->setField('tag', new SelectEntityField('tag', new ListTags(), $tagRepresenter, $this->dispatcher));
                    }),

            AddTag::class => $this->representAction(
                    function (GenericActionRepresenter $remove) use ($tagRepresenter) {
                        $remove->setField('tag', new SelectEntityField('tag', new ListTags(), $tagRepresenter, $this->dispatcher));
                    }),

            \DateTime::class => $this->representEntity(
                    [],
                    [],
                    function (GenericEntityRepresenter $representer) {
                        $representer->setStringifier(function (\DateTime $dateTime) {
                            return $dateTime->format('Y-m-d H:i:s');
                        });
                    }
                ),
        ];
    }

    private function representEntity($queries = [], $commands = [], $callback = null) {
        $representer = new GenericEntityRepresenter();
        foreach ($queries as $query => $handler) {
            $representer->addAction(new ActionGenerator($query));
            $this->dispatcher->addActionHandler($query, $handler);
        }
        foreach ($commands as $command => $handler) {
            $representer->addAction(new ActionGenerator($command));
            $this->dispatcher->addActionHandler($command, $handler);
        }
        if ($callback) {
            call_user_func($callback, $representer);
        }
        return $representer;
    }

    private function representAction($callback = null) {
        $representer = new GenericActionRepresenter($this->factory);
        if ($callback) {
            call_user_func($callback, $representer);
        }
        return $representer;
    }

    /**
     * @param \watoki\qrator\Representer[] $representers
     */
    private function register($representers) {
        foreach ($representers as $class => $representer) {
            $this->registry->register($class, $representer);
        }
    }
}