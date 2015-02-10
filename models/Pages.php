<?php


namespace models;

use \db\SModel;

class Pages extends SModel
{

    public $table = 'pages';

    public $primaryKey = 'id';

/*
    public static function model($className = __CLASS__)
    {
        //$className = __CLASS__;

        $model = parent::model($className);
        //var_dump($model);
        return $model;
    }*/

    public function init()
    {
        # .table и primaryKey может быть указан в методе init() или в занчении свойства
        $this->table = 'pages';
        $this->primaryKey = 'id';
    }


    public function allPages()
    {
        //$data = $this->spdo()->querySelect('*',$this->table,'public = 1')->fetchAll();
        $data = $this->spdo()->querySql('select * from pages')->fetchAll();
        return $data;
    }

    public function allPagesByUserId($userid)
    {
        $data = $this->select('*')
            ->from('pages p')
            ->leftJoin('users u','u.id = p.userid')
            ->where('userid = :uid')
            ->executeAll([
                ':uid' => 2
            ]);

        return $data;
    }

}