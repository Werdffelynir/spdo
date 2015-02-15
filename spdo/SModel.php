<?php

namespace spdo;


class SModel extends SBuilder
{
    /** @var null|string  */
    public $table = null;

    /** @var null|string  */
    public $primaryKey = null;

    /** @var array  */
    private static $modelsInstances = [];


    public function __construct($connectName='db')
    {
        parent::__construct($connectName);
        $this->calledTable();
        $this->init();
    }


    public function init(){}


    private function calledTable()
    {
        if(!$this->table){
            $called = str_replace('\\', '\/', get_called_class() ) ;
            $table = strtolower(substr($called, strrpos($called, '/')+1));
            $this->table = $table;
        }
    }


    /**
     * @param string|SModel $className
     * @return mixed|SModel
     */
    public static function model($className=null)
    {
        if($className==null)
            $className = get_called_class();

        if (isset(self::$modelsInstances[$className])) {
            return self::$modelsInstances[$className];
        } else {
            $model = self::$modelsInstances[$className] = new $className();
            return $model;
        }
    }


    public function comboSelect($column, $criteria = '', array $parameters=[])
    {
        return $this->querySelect($column, $this->table, $criteria, $parameters);
    }

    public function comboInsert($columnData)
    {
        return $this->queryInsert($this->table, $columnData);
    }

    public function comboUpdate(array $columnData, $criteria, $parameters=[])
    {
        return $this->queryUpdate($this->table, $columnData, $criteria, $parameters);
    }

    public function comboDelete($criteria, array $parameters=[])
    {
        return $this->queryDelete($this->table, $criteria, $parameters);
    }

}