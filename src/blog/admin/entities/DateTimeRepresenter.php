<?php
namespace blog\admin\entities;

use watoki\qrator\representer\basic\BasicEntityRepresenter;

class DateTimeRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return \DateTime::class;
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public function toString($dateTime) {
        return $dateTime->format('Y-m-d H:i:s');
    }
}