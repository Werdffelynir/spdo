<?php

namespace db;


class SModel extends SBuilder
{

    /** @var null|string  */
    public $table = null;

    /** @var null|string  */
    public $primaryKey = null;

    /** @var array  */
    private static $models = [];

    /** @var bool|string  */
    protected $connectName = null;

    public function __construct($connectName=false)
    {
        parent::__construct($connectName);

        $this->calledTable();
        $this->init();
    }


    public function init(){}

    /**
     * @param string $className

    public static function model($className = __CLASS__)
    {
        if (isset(self::$models[$className])) {
            return self::$models[$className];
        } else {

            $model = self::$models[$className] = new $className();
            return $model;
        }
    } */


    private function calledTable()
    {
        if(!$this->table){
            $called = str_replace('\\', '\/', get_called_class() ) ;
            $table = strtolower(substr($called, strrpos($called, '/')+1));
            $this->table = $table;
        }
    }

}