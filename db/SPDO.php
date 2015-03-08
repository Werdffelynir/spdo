<?php

class SPDO extends \PDO
{

    /** @var string  */
    private $error;

    /** @var string  */
    private $sql;

    /** @var null|array  */
    private $bind;


    /**
     * @param string    $dsn
     * @param string    $username
     * @param string    $passwd
     * @param array     $options
     */
    public function __construct($dsn, $username='', $passwd='', array $options=[]) {
        empty($options) AND $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ];

        try {
            parent::__construct($dsn, $username, $passwd, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }

    }


    /**
     * @param   string      $sql
     * @param   string|array $bind
     * @param   bool        $fetchAll
     * @return  bool|int|array|object
     */
    public function run($sql, $bind=null, $fetchAll=true) {
        $this->clear();
        $this->sql  = trim($sql);
        $this->bind = empty($bind) ? null : (array) $bind;

        try {
            $pdoStmt = $this->prepare($this->sql);
            if($pdoStmt->execute($this->bind) !== false)
            {
                $first = strtolower(substr($this->sql,0,strpos($this->sql,' ')));
                switch($first){
                    case 'select':
                    case 'pragma':
                    case 'describe':
                        if($fetchAll)
                            return $pdoStmt->fetchAll();
                        else
                            return $pdoStmt->fetch();
                    case 'insert':
                    case 'update':
                    case 'delete':
                        return $pdoStmt->rowCount();
                    default:
                        return false;
                }
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }


    /**
     * @param string    $sql
     * @param string|array $bind
     * @return array|bool|int|object
     */
    public function executeOne($sql, $bind=null) {
        return $this->run($sql, $bind, $fetchAll=false);
    }


    /**
     * @param string    $sql
     * @param string|array $bind
     * @return array|bool|int|object
     */
    public function executeAll($sql, $bind=null) {
        return $this->run($sql, $bind, $fetchAll=true);
    }


    /**
     * @param string    $table
     * @return array
     */
     public function tableInfo($table) {
        $driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);

        if($driver == 'sqlite') {
            $sql = "PRAGMA table_info('" . $table . "');";
            $key = "name";
        } elseif($driver == 'mysql') {
            $sql = "DESCRIBE " . $table . ";";
            $key = "Field";
        } else {
            $sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
            $key = "column_name";
        }

        if(false !== ($columns = $this->run($sql))) {
            return $columns;
        }
        return array();
    }


    /**
     * @param string    $fields
     * @param string    $table
     * @param string    $where
     * @param string|array $bind
     * @param bool      $fetchAll
     * @return array|bool|int|object
     */
    public function select($fields, $table, $where="", $bind=null, $fetchAll=true) {
        $sql = "SELECT " . $fields . " FROM " . $table;
        if(!empty($where))
            $sql .= " WHERE " . $where;
        $sql .= ";";
        return $this->run($sql, $bind, $fetchAll);
    }


    /**
     * @param string    $table
     * @param array     $columnData
     * @return array|bool|int|object
     */
    public function insert($table, array $columnData) {
        $columns = array_keys($columnData);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s);",
            $table,
            implode(', ', $columns),
            implode(', ', array_fill(0, count($columnData), '?'))
        );
        return $this->run($sql, array_values($columnData));
    }


    /**
     * @param string    $table
     * @param string    $where
     * @param string    $bind
     * @return array|bool|int|object
     */
    public function delete($table, $where, $bind=null) {
        $sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
        return $this->run($sql, $bind);
    }


    /**
     * @param string    $table
     * @param array     $columnData
     * @param string    $where
     * @param string|array $bind
     * @return array|bool|int|object
     */
    public function update($table, array $columnData, $where, $bind=null) {
        $columns = array_keys($columnData);
        $where = preg_replace('|:\w+|','?', $where);
        if(empty($bind))
            $bind = array_values($columnData);
        else
            $bind = array_values(array_merge($columnData, (array) $bind));
        $sql = sprintf("UPDATE %s SET %s WHERE %s;",
            $table,
            implode('=?, ', $columns) . '=?',
            $where
        );
        return $this->run($sql, $bind);
    }


    /**
     *
     */
    private function clear() {
        $this->error = null;
        $this->bind = null;
        $this->sql = null;
    }


    /**
     * @param bool|string $row can take params: error, sql or bind, default false
     * @return array|bool
     */
    public function getError($row=false) {
        if(!empty($this->error)) {
            $eData = [
                'error'=>$this->error,
                'sql'=>$this->sql,
                'bind'=>$this->bind
            ];
            if(isset($eData[$row]))
                return $eData[$row];
            return $eData;
        }else
            return false;
    }


}















