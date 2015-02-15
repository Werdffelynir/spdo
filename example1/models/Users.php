<?php


namespace models;

use \spdo\SModel;

class Users extends SModel
{

    public $table = 'users';

    public $primaryKey = 'id';

    public function byId($userid)
    {
        return $this->select('*')
            ->from('users u')
            ->where('u.id = :id')
            ->executeOne([':id' => $userid]);
    }

}