<?php

namespace spdo;


class SPDOException extends \RuntimeException {
    public $errorInfo;
}

class SPDO
{

    private static $configureStack = [];

    /** @var \PDO $dbh database handle */
    private $dbh;

    /** @var \PDOStatement $sth statement handle */
    private $sth;

    /** @var array  */
    private $sql = ['prepare'=>null,'parameters'=>null];

    /** @var null|string */
    private $connectName = null;


    public function __construct($connectName='db', $initConnect=true)
    {
        if(empty(self::$configureStack))
            throw new SPDOException("SPDO::setConfigure() the connection settings to the database must be installed before");
        if($initConnect)
            $this->initConnect($connectName);
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
     *              'username' => 'root',
     *              'password' => '',
     *              'options' => [],
     *          ]
     *  ]);
     * @param array $configure
     */
    public static function setConfigure(array $configure)
    {
        foreach ($configure as $name => $val) {
            self::$configureStack[$name] = [
                'dns' => $val['dns'],
                'username' => !empty($val['username'])?$val['username']:null,
                'password' => !empty($val['password'])?$val['password']:null,
                'options'  => !empty($val['options'])?$val['options']:[]
            ];
        }
    }


    /** @var array $instances */
    private static $instances = [];


    /**
     * @param string $connectName
     * @return null|SPDO
     */
    protected static function getInstance($connectName)
    {
        if(!isset(self::$instances[$connectName]) || self::$instances[$connectName] == null){
            self::$instances[$connectName] = new self($connectName);
        }
        return self::$instances[$connectName];
    }


    /**
     * Статическая инициализация подключения по connectName
     *
     * @param string $connectName
     * @return SPDO|null
     */
    public static function init($connectName='db')
    {
        /** @var SPDO $instance */
        $instance = self::getInstance($connectName);
        return self::getInstance($connectName);
    }


    /**
     * Инициализация подключения по connectName
     *
     * @param string $connectName
     * @return SPDO|SPDOException
     */
    public function initConnect($connectName='db')
    {
        $this->clearConnect(false);
        $connectName = $connectName=='db' ? key(self::$configureStack) : $connectName;

        if(!empty(self::$configureStack[$connectName]))
        {
            if($this->dbh == null || $this->connectName != $connectName)
            {
                $configure = self::$configureStack[$connectName];
                $dbHandle = $this->_connector($configure['dns'], $configure['username'], $configure['password'], $configure['options']);
                if($dbHandle){
                    $this->connectName = $connectName;
                    $this->dbh = $dbHandle;
                }
                else
                    throw new SPDOException('Failed to create object instance \ PDO initialization connection parameters');
            }
        }
        else
            throw new SPDOException("Error initializing, connectName: $connectName not found in the configuration of connection parameters");
        return $this;
    }


    /**
     * @param $dsn
     * @param $username
     * @param $password
     * @param $options
     * @return \PDO|SPDOException
     */
    private function _connector($dsn, $username, $password, $options)
    {
        try {
            $dbh = new \PDO($dsn, $username, $password, $options);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $dbh->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES \'UTF8\'');
            return $dbh;
        }
        catch(SPDOException $e) {
            echo $e->getMessage();
        }
        throw new SPDOException("Ошибка при создании подключения new PDO. Возможны ошибки в параметрах класса dsn, username, password");
    }


    /**
     * Cleaning params or fulled close connection
     * @param bool $completeClose
     * @return void
     */
    public function clearConnect($completeClose = true)
    {
        $this->sql = ['prepare'=>null,'parameters'=>null];
        $this->sth = null;
        if($completeClose) {
            self::$instance = null;
            $this->connectName = null;
            $this->dbh = null;
        }
    }


    /**
     * Возвращает текущее имя подключения connectName или сравнивает параметр с текущим.
     * @param string $connectName
     * @return bool|string
     */
    public function getConnectName($connectName='')
    {
        return $connectName == '' ? $this->connectName : $connectName === $this->connectName;
    }


    /**
     * Returns the last line with the parameters SQL parameters
     * @param bool $withParameters
     * @return string
     */
    public function getLastSql($withParameters=true)
    {
        if($withParameters){
            $_sql = "{".$this->sql['prepare']."}\n";
            foreach((array)$this->sql['parameters'] as $place => $value)
                $_sql .= 'bind '. ((is_numeric($place)) ? '?'.(string)($place+1) : $place).' = '.$value ."\n";
            return $_sql;
        }
        return $this->sql['prepare'];
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
     * @param $prepare
     * @param array $parameters
     * @return bool | \PDOStatement
     */
    public function querySql($prepare, array $parameters=[])
    {
        if($this->dbh)
        {
            $this->clearConnect(false);
            $this->sql['prepare'] = $prepare;
            $this->sql['parameters'] = !empty($parameters)?$parameters:[];

            if(is_callable($this->callBefore)) call_user_func($this->callBefore);

            $this->sth = $this->dbh->prepare($this->sql['prepare']);
            $this->sth->execute($this->sql['parameters']);

            if(is_callable($this->callAfter)) call_user_func($this->callAfter);

            return $this->sth;
        }
        throw new SPDOException('Ошибка не создн экземпляр объекта \PDO $this->dbh. Необходима инициализация подключения.');
    }


    /** @var null|callable */
    private $callBefore = null;

    /** @var null|callable  */
    private $callAfter = null;


    /**
     * Устанавлевает и вызывает callable перед выполнением запроса querySql()
     * Прим: все запросы $this->query[Insert,Select,Update,Delete]() работают через $this->querySql()
     *
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
     *<pre>
     * Example:
     * ->queryInsert('table',
     *      [
     *          'column1' => 'some data',
     *          'column2' => 123456789,
     *      ]
     *  );
     *</pre>
     *
     * @param $tableName
     * @param array $columnData
     * @return mixed Returns the ID of the last inserted row or sequence value
     */
    public function queryInsert($tableName, array $columnData)
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
     * ->queryUpdate('table',
     *      [
     *       'val1'=>'some data',
     *       'val2'=>'some data'
     *      ],
     *      'type = ? AND subtype = ?', [ 'data', 'subdata' ]
     *  );
     *</pre>
     *
     * Сформирует что-то подобное:
     * UPDATE table SET val1 = ?, val2 = ? WHERE type = ? AND subtype = ?
     * и $parameters подготовит со всеми значениями для запроса.
     *
     * @param string $tableName
     * @param array $columnData
     * @param string $criteria
     * @param array $parameters
     * @return string Returned number of rows affected by the last SQL statement
     */
    public function queryUpdate($tableName, array $columnData, $criteria, $parameters=[])
    {
        $columns = array_keys($columnData);
        $criteria = preg_replace('|:\w+|',' ? ', $criteria);
        $parameters = array_values(array_merge($columnData,$parameters));

        $sql = sprintf("UPDATE %s SET %s WHERE %s",
            $tableName,
            implode(' = ?, ', $columns) . ' = ? ',
            $criteria
        );

        return $this->querySql($sql, $parameters)->rowCount();
    }


    /**
     * Wrapper for query such as SELECT
     *
     *<pre>
     * Example:
     * ->querySelect('column1, column2', 'table', 'type = ? AND subtype = ?', ['my_type','my_subtype'])
     *  OR
     * ->querySelect('*', 'table', 'id<:id AND author LIKE :at', [':id'=>'10',':at'=>'%dmi%']);
     *
     * returns an object PDOStatement, use standard methods for its further work,
     * such fetch() OR fetchAll().
     *</pre>
     *
     * @param string $column
     * @param string $tableName
     * @param string $criteria
     * @param array  $parameters
     * @return bool|\PDOStatement
     */
    public function querySelect($column, $tableName, $criteria = '', array $parameters=[])
    {
        $where = !empty($criteria)?' WHERE ':'';
        $sql = sprintf("SELECT %s FROM %s",
            $column,
            $tableName.$where.$criteria
        );

        return $this->querySql($sql, $parameters);
    }

    /**
     * Wrapper for query such as DELETE
     *<pre>
     * Example:
     * ->queryDelete('table','type = ? AND subtype = ?', [ 'data', 'subdata' ]);
     *</pre>
     *
     * @param $tableName
     * @param $criteria
     * @param array $parameters
     * @return string Returned number of rows affected by the last SQL statement
     */
    public function queryDelete($tableName, $criteria, array $parameters=[])
    {

        $sql = sprintf("DELETE FROM %s WHERE %s",
            $tableName,
            $criteria
        );
        return $this->querySql($sql, $parameters)->rowCount();
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
     * Определение начало транзакции.
     * Поле определения метода необходимо подтвердить или опровергнуть транзакцию.
     * @return bool
     */
    public function transactionBegin()
    {
        return $this->dbh->beginTransaction();
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
     * Подтверждение транзакции
     * @return bool
     */
    public function transactionCommit()
    {
        return $this->dbh->commit();
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