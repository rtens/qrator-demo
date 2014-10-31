<?php
namespace blog\model;

use blog\model\commands\CreateTag;

class TagRepository extends Repository {

    public function listTags() {
        $blog = $this->read();
        return array_map(function ($tag) {
            return new Tag($tag);
        }, array_keys($blog['tags']));
    }

    public function createTag(CreateTag $command) {
        $blog = $this->read();
        $blog['tags'][$command->name] = [];
        $this->write($blog);
    }

    public function readTagById($tagId) {
        return new Tag($tagId);
    }

} 