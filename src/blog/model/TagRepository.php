<?php
namespace blog\model;

class TagRepository extends Repository {

    public function listTags() {
        $blog = $this->read();
        return array_map(function ($tag) {
            return new Tag($tag);
        }, array_keys($blog['tags']));
    }

    public function createTag($name) {
        $blog = $this->read();
        $blog['tags'][$name] = [];
        $this->write($blog);
    }

    public function readTagById($tagId) {
        return new Tag($tagId);
    }

} 