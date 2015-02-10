<?php


namespace db;



class SBuilder
{


    public $spdo = null;


    public function __construct($connectName=false)
    {
        $this->spdo = new SPDO($connectName);
    }


    /**
     * @param $connectName
     * @return SPDO | null
     */
    public function initConnect($connectName)
    {
        return $this->spdo->initConnect($connectName);
    }


    /** @var SBuilder | null $instance */
    private static $instance = null;


    /**
     * @return SBuilder
     */
    private static function getInstance()
    {
        if(self::$instance==null){
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @param $connectName
     * @return SPDO | null
     */
    public static function initStaticConnect($connectName)
    {
        $instance = self::getInstance();
        return $instance->initConnect($connectName);
    }

    public function getConnectName($connectName=false)
    {
        return $this->spdo->getConnectName($connectName);
    }



    public function createCommand($sql, $prepare=[])
    {
        return $this->sql;
    }

    public $sql = '';

    /**
     * @param $call
     * @param $params
     * @return SBuilder
     */
    public function __call($call, $params)
    {
        $this->sql .= "\n".$call.' '.join(' ',$params);
        return $this;
    }

    /*
     * Wrapper for PDOStatement::bindValue | PDOStatement::bindParam
     *
     * @param mixed $parameter
     * @param mixed $value
     * @param int $dataType use \PDO::PARAM_*
     * @param int $length
     * @param mixed $driverOptions
     * @return bool|SPDO

    public function bind($parameter, $value, $dataType=\PDO::PARAM_STR, $length=0, $driverOptions=[])
    {
        if($this->sth) {
            $isBind = $this->sth->bindParam($parameter, $value, $dataType, $length, $driverOptions);
            if($isBind)
                return $this;
        }
            return false;
    }
     */

    public function select($columns){}
    public function insert($columns){}
    public function update($columns){}
    public function delete($columns){}

    public function from($columns){}
    public function where($columns){}
    public function join($columns){}
    public function leftJoin($columns){}
    public function innerJoin($columns){}
    public function outerJoin($columns){}
    public function rightJoin($columns){}
    public function orderBy($columns){}
    public function groupBy($columns){}
    public function limit($columns){}
    public function offset($columns){}
    public function set($columns){}
    public function values($columns){}
    public function ____($columns){}
    public function count($columns){}

}



