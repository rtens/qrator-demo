<?php
namespace blog\model;

class Repository {

    protected function read() {
        return json_decode(file_get_contents(__DIR__ . '/../blog.json'), true);
    }

    protected function write($data) {
        file_put_contents(__DIR__ . '/../blog.json', json_encode($data));
    }

} 