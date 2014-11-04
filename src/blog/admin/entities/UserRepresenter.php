<?php
namespace blog\admin\entities;

use blog\model\commands\DeleteUser;
use blog\model\queries\ListUsers;
use blog\model\queries\ReadUser;
use blog\model\User;
use watoki\qrator\representer\ActionGenerator;
use watoki\qrator\representer\basic\BasicEntityRepresenter;

class UserRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return User::class;
    }

    public function getActions() {
        return $this->wrapInActionGenerators([
            ReadUser::class,
            DeleteUser::class,
        ]);
    }

    /**
     * @param User $object
     * @return string
     */
    public function toString($object) {
        return $object->name;
    }

    public function getListAction() {
        return new ActionGenerator(ListUsers::class);
    }
}