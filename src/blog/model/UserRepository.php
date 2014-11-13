<?php
namespace blog\model;

use blog\model\commands\CreateUser;
use blog\model\commands\DeleteUser;
use blog\model\queries\ReadUser;

class UserRepository extends Repository {

    public function createUser(CreateUser $query) {
        $blog = $this->read();
        $id = uniqid();
        $row = [
            'name' => $query->name,
            'email' => $query->email
        ];
        $blog['users'][$id] = $row;
        $this->write($blog);
        return $this->inflate($id, $row);
    }

    public function listUsers() {
        $blog = $this->read();
        $users = isset($blog['users']) ? $blog['users'] : [];
        array_walk($users, function (&$row, $id) {
            $row = $this->inflate(new UserId($id), $row);
        });
        return array_values($users);
    }

    public function deleteUser(DeleteUser $command) {
        $blog = $this->read();
        foreach ($blog['posts'] as $post) {
            if ($post['author'] == $command->id) {
                throw new \Exception("Cannot delete user who is author of post '{$post['title']}'.");
            }
        }
        unset($blog['users'][(string)$command->id]);
        $this->write($blog);
        return "User deleted";
    }

    public function readUserById(UserId $id) {
        $blog = $this->read();
        return $this->inflate($id, $blog['users'][(string)$id]);
    }

    public function readUser(ReadUser $query) {
        return $this->readUserById(new UserId($query->id));
    }

    private function inflate(UserId $id, $row) {
        return new User($id, $row['email'], $row['name']);
    }

} 