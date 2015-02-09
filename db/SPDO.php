<?php
/**
 * Simple PDO wrapper
 *
 * @link https://github.com/Werdffelynir/spdo
 * @author OL Werdffelynir <werdffelynir@gmail.com>
 * @created 08.02.15
 * @license  GNU AGPLv3 https://gnu.org/licenses/agpl.html
 * @since 0.1
 */

/**
 * Core file
 */


namespace db;

use \PDO;
use \PDOStatement;

class SPDO
{
    private static $configureStack = [];

    /** @var \PDO $dbh database handle */
    private $dbh;

    /** @var \PDOStatement $sth statement handle */
    private $sth;

    /** @var array  */
    private $sql = ['sql'=>null,'prepare'=>null];

    /** @var null|string */
    private $connectName = null;


    /**
     * Return current connection name or compare $connectName to current connection name
     *
     * @param bool | string $connectName
     * @return bool | null | string
     */
    public function getConnectName($connectName=false)
    {
        if($connectName===false)
            return $this->connectName;
        return $connectName===$this->connectName;
    }


    /**
     * Returned current object database handle
     * <pre>
     * PDO::
     * beginTransaction()
     * commit()
     * errorCode()
     * errorInfo()
     * exec()
     * getAttribute()
     * getAvailableDrivers()
     * inTransaction()
     * lastInsertId()
     * prepare()
     * query()
     * quote()
     * rollBack()
     * setAttribute()
     * </pre>
     *
     * @return null|\PDO
     */
    public function dbh()
    {
        return $this->dbh;
    }


    /**
     * Returned current object statement derivative from PDO
     * <pre>
     * PDOStatement::
     * $queryString
     * bindColumn()
     * bindParam()
     * bindValue()
     * closeCursor()
     * columnCount()
     * debugDumpParams()
     * errorCode()
     * errorInfo()
     * execute()
     * fetch()
     * fetchAll()
     * fetchColumn()
     * fetchObject()
     * getAttribute()
     * getColumnMeta()
     * nextRowset()
     * rowCount()
     * setAttribute()
     * setFetchMode()
     * </pre>
     *
     * @return null | \PDOStatement
     */
    public function sth()
    {
        return $this->sth;
    }


    /**
     * Returns the last line with the prepared SQL parameters
     * @param bool $withPrepare
     * @return string
     */
    public function getLastSql($withPrepare=true)
    {
        if($withPrepare){
            $_sql = "{".$this->sql['sql']."}\n";
            foreach((array)$this->sql['prepare'] as $place => $value)
                $_sql .= 'bind '. ((is_numeric($place)) ? '?'.(string)($place+1) : $place).' = '.$value ."\n";
            return $_sql;
        }
        return $this->sql['sql'];
    }


    /**
     * Принимает массив конфигурационных праметров подключения.
     *
     * Example:
     * CLASS::setConfigure(
     *  [
     *      'db' =>
     *          [
     *              'dns' => 'sqlite:path/to/database.sqlite'
     *          ],
     *      'mySql' =>
     *          [
     *              'dns' => 'mysql:host=localhost;dbname=database',
     *              'user' => 'root',
     *              'password' => '',
     *          ]
     *  ]);
     * @param array $conf
     */
    public static function setConfigure(array $conf)
    {
        foreach ($conf as $name => $val) {
            self::$configureStack[$name] = [
                'dns' => $val['dns'],
                'user' => !empty($val['user'])?$val['user']:null,
                'password' => !empty($val['password'])?$val['password']:null
            ];
        }
    }


    public function __construct($connectName=false)
    {
        if(empty(self::$configureStack)) {
            echo 'configure is empty';
        }
        if($connectName)
            $this->initConnect($connectName);
    }


    /**
     * Set the connection to the database on name connect() with the configuration
     * parameters note in the parameter method setConfigure();
     *
     * @param string $connectName
     * @param string $sql
     * @param array $prepare
     * @return $this | \PDOStatement | null
     */
    public function initConnect($connectName, $sql='', $prepare = [])
    {
        $this->clean(false);

        if(!empty(self::$configureStack[$connectName]))
        {
            if($this->dbh == null && $this->connectName != $connectName)
            {
                $cnf = self::$configureStack[$connectName];
                $dbh = $this->connect($cnf['dns'],$cnf['user'],$cnf['password']);

                $this->connectName = $connectName;
                $this->dbh = $dbh;
            }
            else
                return $this->dbh;

            # .if the transmitted argument(s)
            if(empty($sql)){
                return $this;
            }else{
                $sth = $this->querySql($sql, $prepare);
                return $sth;
            }
        }
        else
            return false;
    }


    /** @var SPDO null */
    private static $instance = null;


    /**
     * @return SPDO
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
     * @param $sql
     * @param $prepare
     * @return  SPDO | \PDOStatement
     */
    public static function initStaticConnect($connectName, $sql='', $prepare = [])
    {
        $dbCall = self::getInstance();
        return $dbCall->initConnect($connectName, $sql, $prepare);
    }


    /**
     * @param $dsn
     * @param $user
     * @param $password
     * @return \PDO
     */
    private function connect($dsn, $user, $password)
    {
        try {
            $dbh = new \PDO($dsn, $user, $password);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $dbh->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES \'UTF8\'');
            return $dbh;
        }
        catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }


    /**
     * Cleaning params connection
     *
     * @param bool $all
     */
    public function clean($all=true)
    {
        $this->sql = ['sql'=>null,'prepare'=>null];
        $this->sth = null;
        if($all){
            $this->connectName = null;
            $this->dbh = null;
        }
    }


    /* W R A P P E R S */

    /**
     * Wrapper for simple sql query
     *<pre>
     * Example:
     *  ->querySql('SELECT * FROM table')
     *  ->querySql('SELECT * FROM table WHERE id = ? AND role = ?', [1, 'some']);
     *  ->querySql('SELECT * FROM table WHERE type = :type AND role = :role',
     *      [
     *          ':type'=>'some',
     *          ':role'=>'some'
     *      ]
     *  );
     *</pre>
     *
     * @param $sql
     * @param array $prepare
     * @return bool | \PDOStatement
     */
    public function querySql($sql, array $prepare=[])
    {
        if($this->dbh)
        {
            $this->clean(false);
            $this->sql['sql'] = $sql;
            $this->sql['prepare'] = !empty($prepare)?$prepare:[];

            if(is_callable($this->callBefore)) call_user_func($this->callBefore);

            $this->sth = $this->dbh->prepare($this->sql['sql']);
            $this->sth->execute($this->sql['prepare']);

            if(is_callable($this->callAfter)) call_user_func($this->callAfter);

            return $this->sth;
        }else{
            return false;
        }
    }


    /** @var null|callable */
    private $callBefore= null;

    /** @var null|callable  */
    private $callAfter= null;

    /**
     * Устанавлевает и вызывает callable перед выполнением запроса querySql()
     * @param callable $parameters
     */
    public function callAfterQuery(callable $parameters)
    {
        $this->callAfter = $parameters;
    }

    /**
     * Устанавлевает и вызывает callable после выполнением запроса querySql()
     * @param callable $parameters
     */
    public function callBeforeQuery(callable $parameters)
    {
        $this->callBefore = $parameters;
    }


    /**
     * Wrapper for query such as INSERT
     *
     * Example:
     * ->insert('table',
     *      [
     *          'column1' => 'some data',
     *          'column2' => 123456789,
     *      ]
     *  );
     *
     * @param $tableName
     * @param array $columnData
     * @return mixed Returns the ID of the last inserted row or sequence value
     */
    public function insert($tableName, array $columnData)
    {
        $columns = array_keys($columnData);

        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)",
            $tableName,
            implode(', ', $columns),
            implode(', ', array_fill(0, count($columnData), '?'))
        );

        $this->querySql($sql, array_values($columnData));
        return $this->lastInsertId();
    }

    /**
     * Wrapper for query such as UPDATE
     *<pre>
     * Example:
     * ->update('table',
     *      [
     *       'val1'=>'some data',
     *       'val2'=>'some data'
     *      ],
     *      'type = ? AND subtype = ?', [ 'data', 'subdata' ]
     *  );
     * </pre>
     *
     * Сформирует что-то подобное:
     * UPDATE table SET val1 = ?, val2 = ? WHERE type = ? AND subtype = ?
     * и $prepare подготовит со всеми значениями для запроса.
     *
     * @param string $tableName
     * @param array $columnData
     * @param string $criteria
     * @param array $prepare
     * @return string Returned number of rows affected by the last SQL statement
     */
    public function update($tableName, array $columnData, $criteria, $prepare=[])
    {
        $columns = array_keys($columnData);
        $criteria = preg_replace('|:\w+|',' ? ', $criteria);
        $prepare = array_values(array_merge($columnData,$prepare));

        $sql = sprintf("UPDATE %s SET %s WHERE %s",
            $tableName,
            implode(' = ?, ', $columns) . ' = ? ',
            $criteria
        );

        return $this->querySql($sql, $prepare)->rowCount();
    }


    /**
     * Wrapper for query such as SELECT
     *
     * Example:
     * ->select('column1, column2', 'table', 'type = ? AND subtype = ?', ['my_type','my_subtype'])
     *  OR
     * ->select('*', 'table', 'id<:id AND author LIKE :at', [':id'=>'10',':at'=>'%dmi%']);
     *
     * returns an object PDOStatement, use standard methods for its further work,
     * such fetch() OR fetchAll().
     *
     * @param string $column
     * @param string $tableName
     * @param string $criteria
     * @param array  $prepare
     * @return bool|\PDOStatement
     */
    public function select($column, $tableName, $criteria = '', array $prepare=[])
    {
        $where = !empty($criteria)?' WHERE ':'';
        $sql = sprintf("SELECT %s FROM %s",
            $column,
            $tableName.$where.$criteria
        );

        return $this->querySql($sql, $prepare);
    }

    /**
     * Wrapper for query such as DELETE
     *<pre>
     * Example:
     * ->delete('table','type = ? AND subtype = ?', [ 'data', 'subdata' ]);
     * </pre>
     * @param $tableName
     * @param $criteria
     * @param array $prepare
     * @return string Returned number of rows affected by the last SQL statement
     */
    public function delete($tableName, $criteria, array $prepare=[])
    {

        $sql = sprintf("DELETE FROM %s WHERE %s",
            $tableName,
            $criteria
        );
        return $this->querySql($sql, $prepare)->rowCount();
    }

    /**
     * Возвращает ид записи после операции запроса INSERT
     * примечание в оф.док
     * @return mixed Returns the ID of the last inserted row or sequence value
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }



    /**
     * Транзакция совокупность запросов базу данных.
     * Поле определения метода необходимо подтвердить или опровергнуть транзакцию.
     * @return bool
     */
    public function transactionBegin()
    {
        return $this->dbh->beginTransaction();
    }


    /**
     * Подтверждение транзакции
     * @return bool
     */
    public function transactionCommit()
    {
        return $this->dbh->commit();
    }

    /**
     * Checks if inside a transaction
     * @return bool
     */
    public function transactionIn()
    {
        return $this->dbh->inTransaction();
    }

    /**
     * Отмена и откат запросов транзакции
     * @return bool
     */
    public function transactionRollback()
    {
        return $this->dbh->rollback();
    }



}