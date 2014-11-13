<?php
namespace blog\model;

class UserId {

    private $id;

    function __construct($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function __toString() {
        return $this->id;
    }

} 