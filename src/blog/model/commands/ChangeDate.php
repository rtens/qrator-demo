<?php
namespace blog\model\commands;

class ChangeDate {

    /** @var string|\blog\model\Post-ID */
    public $id;

    /** @var \DateTime */
    public $date;

    function __construct($id, $date) {
        $this->date = $date;
        $this->id = $id;
    }

} 