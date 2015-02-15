<?php
/**
 * Simple PDO wrapper
 *
 * @link https://github.com/Werdffelynir/spdo
 * @author OL Werdffelynir <werdffelynir@gmail.com>
 * @created 08.02.15
 * @license  GNU AGPLv3 https://gnu.org/licenses/agpl.html
 */

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

    /**
     * @param string $column
     * @param string $criteria
     * @param array $parameters
     * @return bool|\PDOStatement
     */
    public function tableSelect($column, $criteria = '', array $parameters=[])
    {
        return $this->querySelect($column, $this->table, $criteria, $parameters);
    }

    /**
     * @param string $columnData
     * @return mixed
     */
    public function tableInsert($columnData)
    {
        return $this->queryInsert($this->table, $columnData);
    }

    /**
     * @param array $columnData
     * @param $criteria
     * @param array $parameters
     * @return string
     */
    public function tableUpdate(array $columnData, $criteria, $parameters=[])
    {
        return $this->queryUpdate($this->table, $columnData, $criteria, $parameters);
    }

    /**
     * @param $criteria
     * @param array $parameters
     * @return string
     */
    public function tableDelete($criteria, array $parameters=[])
    {
        return $this->queryDelete($this->table, $criteria, $parameters);
    }


    /**
     * @param null $condition
     * @param array $conditionParams
     * @return int
     */
    public function tableCount($condition = null, array $conditionParams = [])
    {
        return parent::count($this->table, $condition, $conditionParams);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return parent::querySelect('*', $this->table, $this->primaryKey.'=:id', [':id'=>$id])
            ->fetch();
    }

    /**
     * @param $attr
     * @param $val
     * @return mixed
     */
    public function getOneByAttr($attr,$val)
    {
        return parent::querySelect('*', $this->table, $attr.'=:attr', [':attr'=>$val])
            ->fetch();
    }

    /**
     * @param $attr
     * @param $val
     * @return array
     */
    public function getAllByAttr($attr,$val)
    {
        return parent::querySelect('*', $this->table, $attr.'=:attr', [':attr'=>$val])
            ->fetchAll();
    }


}