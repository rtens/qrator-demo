<?php
namespace blog\admin\entities;

use blog\model\commands\DeleteUser;
use blog\model\queries\ListUsers;
use blog\model\queries\ReadUser;
use blog\model\User;
use watoki\qrator\representer\ActionLink;
use watoki\qrator\representer\basic\BasicEntityRepresenter;

class UserRepresenter extends BasicEntityRepresenter {

    /**
     * @return string
     */
    public function getClass() {
        return User::class;
    }

    /**
     * @param User $entity
     * @return array|\watoki\qrator\representer\ActionLink[]
     */
    public function getActions($entity) {
        return [
            new ActionLink(ReadUser::class, ['id' => $entity->id]),
            new ActionLink(DeleteUser::class, ['id' => $entity->id]),
        ];
    }

    /**
     * @param User $object
     * @return string
     */
    public function toString($object) {
        return $object->name;
    }

    public function getListAction() {
        return new ActionLink(ListUsers::class);
    }
}