<?php


namespace models;

use \spdo\SModel;

class Pages extends SModel
{

    public $table = 'pages';

    public $primaryKey = 'id';


    public function init()
    {
        # .table и primaryKey может быть указан в методе open() или в занчении свойства
        $this->table = 'pages';
        $this->primaryKey = 'id';

        $this->openConnect('db');
    }


    public function all()
    {
        return $this
            ->querySql("SELECT * FROM pages")
            ->fetchAll();
    }

    public function byId($userid)
    {
        return $this->select('*')
            ->from('pages p')
            ->where('p.id = :id')
            ->executeOne([
                ':id' => 2
            ]);
    }


}