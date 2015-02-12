<?php


namespace db;


class SBuilder
{
    /** @var SPDO  */
    private $spdo = null;

    /** @var null | \PDOStatement */
    private $sth = null;

    /** @var null | \PDO */
    private $dbh = null;

    /** @var string  */
    private $buildSql = '';

    /** @var array  */
    private $buildCondition=[];

    /** @var string  */
    private $prepareType = '';

    /** @var SBuilder | null $instance */
    private static $instance = null;

    /**
     * @param bool|string $connectName
     */
    public function __construct($connectName=false)
    {
        $this->initConnect($connectName);
        $this->init();
    }

    public function init(){}

    /**
     * @return SPDO
     */
    public function spdo()
    {
        return $this->spdo;
    }

    /**
     * @param $connectName
     * @return SPDO | null
     */
    public function initConnect($connectName)
    {
        $this->spdo = new SPDO($connectName);
        $this->spdo->initConnect($connectName);
        $this->dbh = $this->spdo->dbh();

        return $this->spdo;
    }


    /**
     * @return SBuilder
     */
    protected static function getInstance()
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

    /**
     * @param bool $connectName
     * @return bool|null|string
     */
    public function getConnectName($connectName=false)
    {
        return $this->spdo->getConnectName($connectName);
    }


    /**
     * Get current builder Sql request
     *
     * @param bool $withPrepare
     * @return string
     */
    public function getSql($withPrepare=true)
    {
        if($withPrepare) {
            $_sql = "{".$this->buildSql."}\n";
            foreach((array)$this->buildCondition as $place => $value)
                $_sql .= 'bind '. ((is_numeric($place)) ? '?'.(string)($place+1) : $place).' = '.$value ."\n";
            return $_sql;
        }
        return $this->buildSql;
    }


    public function select($columns){
        $this->prepareType = 'SELECT';
        $this->buildSql .= ' SELECT '.$columns.' ';
        return $this;
    }
    public function insert($columns){
        $this->prepareType = 'INSERT';
        $this->buildSql .= ' INSERT '.$columns.' ';
        return $this;
    }
    public function insertInto($table){
        $this->prepareType = 'INSERT';
        $this->buildSql .= ' INSERT INTO '.$table.' ';
        return $this;
    }
    public function update($table){
        $this->prepareType = 'UPDATE';
        $this->buildSql .= ' UPDATE '.$table.' ';
        return $this;
    }
    public function delete($table){
        $this->prepareType = 'DELETE';
        $this->buildSql .= ' DELETE FROM '.$table.' ';
        return $this;
    }

    public function from($table){
        $this->buildSql .= ' FROM '.$table.' ';
        return $this;
    }
    public function where($condition){
        $this->buildSql .= ' WHERE '. $condition;
        return $this;
    }
    public function join($table,$condition){
        $this->buildSql .= ' JOIN '. $table . ' ON ('.$condition.')';
        return $this;
    }
    public function leftJoin($table,$condition){
        $this->buildSql .= ' LEFT JOIN '. $table . ' ON ('.$condition.')';
        return $this;
    }
    public function innerJoin($table,$condition){
        $this->buildSql .= ' INNER JOIN '. $table . ' ON ('.$condition.')';
        return $this;
    }
    public function outerJoin($table,$condition){
        $this->buildSql .= ' OUTER JOIN '. $table . ' ON ('.$condition.')';
        return $this;
    }
    public function rightJoin($table,$condition){
        $this->buildSql .= ' RIGHT JOIN '. $table . ' ON ('.$condition.')';
        return $this;
    }
    public function orderBy($columns, $sort='ASC') {
        $this->buildSql .= ' ORDER BY '.$columns.((strtoupper($sort)=='DESC')?' DESC ':' ASC ');
        return $this;
    }
    public function groupBy($columns){
        $this->buildSql .= ' GROUP BY '.$columns ;
        return $this;
    }
    public function set(array $columns){
        $paramsKey = array_keys($columns);
        $this->buildSql .= ' SET '.join(", ",array_map(function($val){return $val."=:".$val;},$paramsKey));
        $this->buildCondition = array_merge($this->buildCondition,$columns);
        return $this;
    }
    public function values(array $columns){
        $paramsKey = array_keys($columns);
        $sql = " (".join(", ",$paramsKey).") VALUES(:".join(", :",$paramsKey).") ";
        $this->buildSql .= $sql;
        $this->buildCondition = array_merge($this->buildCondition,$columns);
        return $this;
    }

    public function clear(){
        $this->buildSql = '';
        $this->buildCondition = [];
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
     */
    public function bind($parameter, $value, $dataType=\PDO::PARAM_STR, $length=0, $driverOptions=[])
    {
        if($this->sth) {
            $this->sth->bindParam($parameter, $value, $dataType, $length, $driverOptions);
        }else{
            $this->condition([$parameter=>$value]);
        }
        return $this;
    }


    public function condition(array $condition){
        $this->buildCondition = array_merge($this->buildCondition,$condition);
        return $this;
    }

    /**
     * Готовит зарпос
     * @param string $prepare
     * @return $this
     */
    public function prepare($prepare='')
    {
        if(empty($prepare))
            $sql = $this->buildSql;
        else
            $sql = $prepare;

        $this->sth = $this->dbh->prepare($sql);
        return $this;
    }

    /**
     * @param array $condition
     * @return int|\PDOStatement
     */
    public function execute(array $condition=[])
    {
        if(!empty($condition))
            $this->buildCondition = array_merge($this->buildCondition,$condition);

        $buildSql = $this->buildSql;
        $buildCondition = $this->buildCondition;

        $this->clear();

        if($this->sth != null){
            $this->sth->execute();
        }else{
            $this->sth = $this->spdo->querySql(
                $buildSql,
                $buildCondition
            );
        }

        # return взависимости от типа запроса
        if($this->prepareType=='INSERT')
            return $this->lastInsertId();
        elseif($this->prepareType=='UPDATE'||$this->prepareType=='DELETE')
            return $this->rowCount();
        else
            return $this->sth;
    }

    public function executeOne(array $condition=[])
    {
        return $this->execute($condition)->fetch();
    }

    public function executeAll(array $condition=[])
    {
        return $this->execute($condition)->fetchAll();
    }

    /**
     * Возвращает ид записи после операции запроса INSERT
     *
     * примечание в оф.док
     * @return mixed Returns the ID of the last inserted row or sequence value
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return $this->sth->rowCount();
    }

}



