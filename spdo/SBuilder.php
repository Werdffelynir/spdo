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


class SBuilder extends SPDO
{

    /** @var array  */
    private $queryType = '';
    private $buildPrepare = '';
    private $buildParameters=[];


    /**
     * Added params to "SELECT $columns "
     * <pre>
     * Example:
     *  ->select('*')
     *  ->select('column, column, column')
     *  ->select('a.column, a.column, b.column')
     * </pre>
     *
     * @param $columns
     * @return $this
     */
    public function select($columns){
        $this->queryType = 'SELECT';
        $this->buildPrepare .= " SELECT $columns ";
        return $this;
    }

    /**
     * Use for insert alternative syntax:
     * INSERT $table SET ( column_name = column_value, ... );
     * <pre>
     * Example:
     *  ->insert($table)
     *      ->set([
     *          column=> value,
     *          column=> value,
     *      ]);
     * </pre>
     *
     * @param string $table
     * @return $this
     */
    public function insert($table){
        $this->queryType = 'INSERT';
        $this->buildPrepare .= " INSERT $table ";
        return $this;
    }

    /**
     * Use for insert default syntax:
     *INSERT INTO $table (column_name, ...) VALUES (column_value, ...);
     * <pre>
     * Example:
     *  ->insertInto($table)
     *      ->values([
     *          column=> value,
     *          column=> value,
     *      ]);
     * </pre>
     *
     * @param string $table
     * @return $this
     */
    public function insertInto($table){
        $this->queryType = 'INSERT';
        $this->buildPrepare .= " INSERT INTO $table ";
        return $this;
    }

    /**
     * Added to builder SQL line "UPDATE $table "
     * <pre>
     * Example:
     *
     * </pre>
     *
     * @param string $table
     * @return $this
     */
    public function update($table){
        $this->queryType = 'UPDATE';
        $this->buildPrepare .= " UPDATE $table ";
        return $this;
    }

    /**
     * <pre>
     * Example:
     *
     * </pre>
     * @param string $table
     * @return $this
     */
    public function delete($table){
        $this->queryType = 'DELETE';
        $this->buildPrepare .= " DELETE FROM $table ";
        return $this;
    }

    /**
     * <pre>
     * Example:
     *
     * </pre>
     * @param string $table
     * @return $this
     */
    public function from($table){
        $this->buildPrepare .= " FROM $table ";
        return $this;
    }

    /**
     * added params after "WHERE $condition "
     * <pre>
     * Example:
     *
     * </pre>
     * @param string $condition
     * @return $this
     */
    public function where($condition){
        $this->buildPrepare .= " WHERE $condition ";
        return $this;
    }

    /**
     * added params to "JOIN $table ON ($condition)"
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function join($table,$condition){
        $this->buildPrepare .= " JOIN $table ON ($condition) ";
        return $this;
    }

    /**
     * added params to "LEFT JOIN $table ON ($condition)"
     * <pre>
     * Example:
     *
     * </pre>
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function leftJoin($table,$condition){
        $this->buildPrepare .= " LEFT JOIN $table ON ($condition) ";
        return $this;
    }

    /**
     * added params to "INNER JOIN $table ON ($condition)"
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function innerJoin($table,$condition){
        $this->buildPrepare .= " INNER JOIN $table ON ($condition) ";
        return $this;
    }

    /**
     * added params to "OUTER JOIN $table ON ($condition)"
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function outerJoin($table,$condition){
        $this->buildPrepare .= " OUTER JOIN $table ON ($condition) ";
        return $this;
    }

    /**
     * added params to "RIGHT JOIN $table ON ($condition)"
     * @param string $table
     * @param string $condition
     * @return $this
     */
    public function rightJoin($table,$condition){
        $this->buildPrepare .= " RIGHT JOIN $table ON ($condition) ";
        return $this;
    }

    /**
     * added params to " ORDER BY $columns $sort"
     * <pre>
     * Example:
     *
     * </pre>
     * @param $columns
     * @param string $sort
     * @return $this
     */
    public function orderBy($columns, $sort='ASC') {
        $this->buildPrepare .= ' ORDER BY '.$columns.((strtoupper($sort)=='DESC')?' DESC ':' ASC ');
        return $this;
    }

    /**
     * added params to " GROUP BY $columns"
     * <pre>
     * Example:
     *
     * </pre>
     * @param $columns
     * @return $this
     */
    public function groupBy($columns){
        $this->buildPrepare .= " GROUP BY $columns ";
        return $this;
    }

    /**
     * Added params form $columns to " SET key = value"
     * <pre>
     * for:
     * INSERT table SET $column key = $column value, [WHERE ...];
     * UPDATE table SET $column key = $column value, [WHERE ...];
     * Example:
     *  $updatesRows = $db->update(table)
     *      ->set([
     *          column => value,
     *          column => value
     *      ])
     *      ->where("column = ?")
     *      ->execute( [14] );
     * </pre>
     * @param array $columns
     * @return $this
     */
    public function set(array $columns){
        $paramsKey = array_keys($columns);
        $this->buildPrepare .= ' SET '.join(", ",array_map(function($val){return $val."=:".$val;},$paramsKey));
        $this->buildParameters = array_merge($this->buildParameters,$columns);
        return $this;
    }

    /**
     * added params form $columns to " ( keys... ) VALUES ( values... )
     * <pre>
     * Example:
     *
     * </pre>
     * @param array $columns
     * @return $this
     */
    public function values(array $columns){
        $paramsKey = array_keys($columns);
        $sql = " (".join(", ",$paramsKey).") VALUES(:".join(", :",$paramsKey).") ";
        $this->buildPrepare .= $sql;
        $this->buildParameters = array_merge($this->buildParameters,$columns);
        return $this;
    }


    /**
     * Clear SQL builder line
     */
    public function clear(){
        $this->sth = null;
        $this->buildPrepare = '';
        $this->buildParameters = [];
    }


    /**
     * Вернет строительный материал SQL строку, перед тем как выполнить. Для debug.
     * Замечание: Параметры очищаються при выполнении. Метод используйте перед execute()
     *
     * @param bool $withParameters
     * @return string
     */
    public function getLastBuildSql($withParameters=true)
    {
        if($withParameters){
            $_sql = "{".$this->buildPrepare."}\n";
            foreach((array)$this->buildParameters as $place => $value)
                $_sql .= 'bind '. ((is_numeric($place)) ? '?'.(string)($place+1) : $place).' = '.$value ."\n";
            return $_sql;
        }
        return $this->buildPrepare;
    }


    /**
     * Обертка для метода PDOStatement::bindParam или PDOStatement::bindValue
     * Wrapper for PDOStatement::bindParam and PDOStatement::bindValue
     * <pre>
     * Example:
     *
     * </pre>
     *
     * @param mixed $parameter
     * @param mixed $value
     * @param int $dataType use \PDO::PARAM_*
     * @param int $length
     * @param mixed $driverOptions
     * @return SBuilder
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


    /**
     * Добавляет
     * <pre>
     * Example:
     *
     * </pre>
     *
     * @param array $condition
     * @return $this
     */
    public function condition(array $condition){
        $this->buildParameters = array_merge($this->buildParameters,$condition);
        return $this;
    }

    /**
     * Подготавлевает запрос методом PDO::prepare, возвращает в $this->dbh экземпляр \PDOStatement
     * Закрывет посторение SQL строки
     * <pre>
     * Example:
     *
     * </pre>
     *
     * @param string $prepare
     * @return $this
     */
    public function prepare($prepare='')
    {
        if(empty($prepare))
            $sql = $this->buildPrepare;
        else
            $sql = $prepare;

        $this->sth = $this->dbh->prepare($sql);
        return $this;
    }

    /**
     * Выполняет подготовленный запрос.
     * <pre>
     * Возвращаемый результат зависит от типа операции
     * SELECT               return \PDOStatement
     * INSERT               return \PDOStatement::lastInsertId()
     * UPDATE and DELETE    return \PDOStatement::rowCount()
     *
     * Example:
     *  ->execute([ params ... ])
     *  [->fetch()]
     *  [->fetchAll()]
     * </pre>
     *
     * @param array $condition
     * @return int|\PDOStatement
     */
    public function execute(array $condition=[])
    {
        if(!empty($condition))
            $this->buildParameters = array_merge($this->buildParameters,$condition);

        $buildPrepare = $this->buildPrepare;
        $buildParameters = $this->buildParameters;

        $this->clear();

        if($this->sth != null){
            $this->sth->execute();
        }else{
            $this->sth = $this->querySql(
                $buildPrepare,
                $buildParameters
            );
        }

        # return взависимости от типа запроса
        if($this->queryType=='INSERT')
            return $this->lastInsertId();
        elseif($this->queryType=='UPDATE'||$this->queryType=='DELETE')
            return $this->rowCount();
        else
            return $this->sth;
    }

    /**
     * Выполняет запрос возвращая выборку одной строки
     *
     * @param array $condition
     * @return mixed
     */
    public function executeOne(array $condition=[])
    {
        return $this->execute($condition)->fetch();
    }

    /**
     * Выполняет запрос возвращая выборку нескольких строк
     *
     * @param array $condition
     * @return array
     */
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


    /**
     * Возвращает количество записей
     * <pre>
     * ->count('table');
     * ->count('table','id >= :num',['num'=>$num]);
     * </pre>
     *
     * @param $table
     * @param string $condition
     * @param array $params
     * @return int
     */
    public function count($table, $condition='', $params=[])
    {
        $fetchAll['count'] = null;

        if(empty($condition))
        {
            $obj = $this->select('COUNT(*) as count')
                ->from($table);
        }
        else
        {
            $obj = $this->select('COUNT(*) as count')
                ->from($table)
                ->where($condition);
        }

        $fetchAll = $obj->executeOne($params);

        return (int) $fetchAll['count'];
    }


    //public function lastId($table, $condition='', $params=[]) {}


}