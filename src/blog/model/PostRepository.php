<?php
namespace blog\model;

use blog\model\commands\AddTag;
use blog\model\commands\ChangeDate;
use blog\model\commands\CreatePost;
use blog\model\commands\DeletePost;
use blog\model\commands\RemoveTag;
use blog\model\commands\UpdatePost;
use blog\model\queries\ListTaggedPosts;
use blog\model\queries\ReadPost;

class PostRepository extends Repository {

    /** @var UserRepository <- */
    public $users;

    /** @var TagRepository <- */
    public $tags;

    public function listPosts() {
        $blog = $this->read();
        $posts = [];
        foreach (isset($blog['posts']) ? $blog['posts'] : [] as $id => $row) {
            $posts[] = $this->inflate($id, $row);
        }
        return $posts;
    }

    function createPost(CreatePost $command) {
        $blog = $this->read();
        $id = uniqid();
        $row = [
            'author' => (string)$command->author,
            'date' => date('c'),
            'title' => $command->title,
            'content' => $command->content,
            'tags' => $command->tags
        ];
        $blog['posts'][$id] = $row;

        $this->write($blog);
        return $this->inflate($id, $row);
    }

    function readPost(ReadPost $query) {
        $blog = $this->read();
        if (!isset($blog['posts'][$query->id])) {
            throw new \Exception("Post [{$query->id}] does not exist.");
        }
        return $this->inflate($query->id, $blog['posts'][$query->id]);
    }

    function updatePost(UpdatePost $command) {
        $blog = $this->read();
        $blog['posts'][(string)$command->id]['title'] = $command->title;
        $blog['posts'][(string)$command->id]['content'] = $command->content;
        $blog['posts'][(string)$command->id]['updated'] = date('c');
        $this->write($blog);
    }

    function changeDate(ChangeDate $command) {
        $blog = $this->read();
        $blog['posts'][(string)$command->id]['date'] = $command->date->format('c');
        $this->write($blog);
    }

    function deletePost(DeletePost $command) {
        $blog = $this->read();
        unset($blog['posts'][(string)$command->id]);
        $this->write($blog);
    }

    function removeTag(RemoveTag $command) {
        $blog = $this->read();
        foreach ($blog['posts'][(string)$command->post]['tags'] as $i => $tagId) {
            if ($tagId == $command->tag) {
                unset($blog['posts'][(string)$command->post]['tags'][$i]);
            }
        }
        $this->write($blog);
    }

    function addTag(AddTag $command) {
        $blog = $this->read();
        $blog['posts'][(string)$command->id]['tags'][] = $command->tag;
        $this->write($blog);
    }

    function listTaggedPosts(ListTaggedPosts $query) {
        $posts = [];
        $blog = $this->read();
        foreach ($blog['posts'] as $id => $post) {
            if (isset($post['tags']) && in_array($query->id, $post['tags'])) {
                $posts[] = $this->inflate($id, $post);
            }
        }
        return $posts;
    }

    private function inflate($id, $row) {
        $author = $this->users->readUserById(new UserId($row['author']));
        $post = new Post($id, $author, $row['title'], $row['content'], new \DateTime($row['date']), new \DateTime(isset($row['updated']) ? $row['updated'] : $row['date']));
        if (isset($row['tags'])) {
            foreach ($row['tags'] as $tagId) {
                $post->tags[] = $this->tags->readTagById($tagId);
            }
        }
        return $post;
    }

} 