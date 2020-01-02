<?php
/**
 * @created: 14.02.2016
 * @update: 02.01.2020
 */


namespace db;

/**
 * Simple PDO wrapper
 * Class SPDO
 * @package db
 */
class SPDO extends \PDO
{

    /** @var string */
    private $error;

    /** @var string */
    private $sql;

    /** @var null|array */
    private $bind;


    /**
     * <pre>
     * Examples for dsn of databases:
     * MS SQL Server    "mssql:host=$host;dbname=DATABASE_NAME"
     * Sybase           "sybase:host=$host;dbname=DATABASE_NAME"
     * MySQL            "mysql:host=$host;dbname=DATABASE_NAME"
     * SQLite           "sqlite:my/database/path/DATABASE_FILE"
     * Oracle
     * </pre>
     * @param string $dsn
     * @param string $username
     * @param string $passwd
     * @param array $options
     */
    public function __construct($dsn, $username = '', $passwd = '', array $options = [])
    {
        empty($options) AND $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ];

        try {
            parent::__construct($dsn, $username, $passwd, $options);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
        }

    }


    /**
     * Attempt to complete the request.
     * The method determines the type of operation to perform the corresponding task.
     * <pre>
     * The examples below are identical, they will return the id of the added record if the database driver allows
     * ->executeQuery('INSERT INTO folks (name, addr, city) values (?, ?, ?)' ['Mr Doctor', 'some street', 'London']);
     * ->executeQuery('INSERT INTO folks (name, addr, city) values (:name, :addr, :city)' [':name'=>'Mr Doctor', ':addr'=>'some street', ':city'=>'London']);
     *
     * The examples below will return a single entry
     * ->executeQuery('SELECT name, addr, city FROM folks WHERE city = :city', [':city'=>'London'], false);
     *
     * </pre>
     * @param string $sql Request with placeholders
     * @param string|array $bind Array of values for binding placeholders
     * @param bool $fetchAll bool kay, select all or one entry
     * @return  bool|int|array|object       Depending on the type of request and attributes \PDO
     */
    public function executeQuery($sql, $bind = null, $fetchAll = true)
    {
        $this->clear();
        $this->sql = trim($sql);
        $this->bind = empty($bind) ? null : (array)$bind;

        try {
            $pdoStmt = $this->prepare($this->sql);
            if ($pdoStmt->execute($this->bind) !== false) {
                $first = strtolower(str_word_count($sql, 1)[0]);
                switch ($first) {
                    case 'select':
                    case 'pragma':
                    case 'describe':
                        if ($fetchAll)
                            return $pdoStmt->fetchAll();
                        else
                            return $pdoStmt->fetch();
                    case 'insert':
                        return $this->lastInsertId();
                    case 'update':
                    case 'delete':
                        return $pdoStmt->rowCount();
                    default:
                        return false;
                }
            }
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * Attempting to execute a single row return query
     *
     * @param string $sql
     * @param string|array $bind
     * @return mixed        Depending on the type of request and attributes \PDO
     */
    public function executeOne($sql, $bind = null)
    {
        return $this->executeQuery($sql, $bind, $fetchAll = false);
    }


    /**
     * Attempting to execute a query that returns multiple rows
     *
     * @param string $sql
     * @param string|array $bind
     * @return mixed        Depending on the type of request and attributes \PDO
     */
    public function executeAll($sql, $bind = null)
    {
        return $this->executeQuery($sql, $bind, $fetchAll = true);
    }


    /**
     * Returns table information
     *
     * @param string $table
     * @return array
     */
    public function tableInfo($table)
    {
        $driver = $this->getAttribute(\PDO::ATTR_DRIVER_NAME);

        if ($driver == 'sqlite') {
            $sql = "PRAGMA table_info('" . $table . "');";
            $key = "name";
        } elseif ($driver == 'mysql') {
            $sql = "DESCRIBE " . $table . ";";
            $key = "Field";
        } else {
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
            $key = "column_name";
        }

        if (false !== ($columns = $this->executeQuery($sql))) {
            return $columns;
        }
        return array();
    }


    /**
     * Simplified data retrieval request
     *
     * <pre>
     * ->select('id, link, title', 'my_table','active=?', [1]);
     * </pre>
     *
     * @param string $fields
     * @param string $table
     * @param string $where
     * @param string|array $bind
     * @param bool $fetchAll
     * @return array|bool|int|object
     */
    public function select($fields, $table, $where = "", $bind = null, $fetchAll = true)
    {
        $sql = "SELECT " . $fields . " FROM " . $table;
        if (!empty($where))
            $sql .= " WHERE " . $where;
        $sql .= ";";
        return $this->executeQuery($sql, $bind, $fetchAll);
    }


    /**
     * Simplified data write request
     *
     * <pre>
     * An example will execute the request and will return lastInsertId, if possible.
     * ->insert('my_table', ['link'=>'some link', 'title'=>'some title']);
     * </pre>
     *
     * @param string $table table name
     * @param array $columnData parameters
     * @return int                  Returns the number of modified rows
     */
    public function insert($table, array $columnData)
    {
        $columns = array_keys($columnData);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s);",
            ' `' . $table . '` ',
            ' `' . implode('`, `', $columns) . '` ',
            implode(', ', array_fill(0, count($columnData), '?'))
        );
        return $this->executeQuery($sql, array_values($columnData));
    }


    /**
     * Simplified data deletion request
     *
     * <pre>
     * An example will execute the request and will return lastInsertId, if possible.
     * ->delete('my_table', 'id = ?', [123]);
     * </pre>
     *
     * @param string $table table name
     * @param string $where conditions with placeholders
     * @param string $bind bind array for placeholders
     * @return int                  Returns the number of modified rows
     */
    public function delete($table, $where, $bind = null)
    {
        $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
        return $this->executeQuery($sql, $bind);
    }

    /**
     * Simplified data update request.
     * Please note that placeholders in this request should only be nameless (WHERE id = ?, title = ?),
     * but if placeholders are called in the request, they will be regenerated into nameless ones, which can lead to
     * unpredictable results.
     * The conversion takes place one after another, and if the positions are different the request will be distorted.
     *
     * <pre>
     * ->update('my_table', ['link'=>'new link', 'title'=>'new title'], 'id = ?', [123]);
     * // Execute SQL request 'UPDATE my_table SET ('link'=?, 'title'=?) WHERE id = ?'
     * </pre>
     *
     * @param string $table
     * @param array $columnData
     * @param string $where
     * @param string|array $bind
     * @return int                  Returns the number of modified rows
     */
    public function update($table, array $columnData, $where, $bind = null)
    {
        $columns = array_keys($columnData);
        $where = preg_replace('|:\w+|', '?', $where);
        if (empty($bind))
            $bind = array_values($columnData);
        else
            $bind = array_values(array_merge($columnData, (array)$bind));
        $sql = sprintf("UPDATE %s SET %s WHERE %s;",
            ' `' . $table . '` ',
            ' `' . implode('`=?, `', $columns) . '` = ? ',
            $where
        );
        return $this->executeQuery($sql, $bind);
    }


    private function clear()
    {
        $this->error = null;
        $this->bind = null;
        $this->sql = null;
    }


    /**
     * Error output if any. Can be used to identify errors
     * @param bool|string $row can take params: error, sql or bind, default false
     * @return array|bool
     */
    public function getError($row = false)
    {
        if (!empty($this->error)) {
            $eData = [
                'error' => $this->error,
                'sql' => $this->sql,
                'bind' => $this->bind
            ];
            if (isset($eData[$row]))
                return $eData[$row];
            return $eData;
        } else
            return false;
    }

}