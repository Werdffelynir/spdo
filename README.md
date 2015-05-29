# Simple PDO

## This Simple PDO wrapper on PHP, client for quick working with database
Class SPDO extends standard PHP PDO, you can use it. 

### Tested on
- SQLite
- MySQL
- Oracle
- PostgreSQL


### Configuration
SPDO class constructor accept configuration of connect to database - like [PDO](http://php.net/manual/en/pdo.construct.php) arguments

example: 
sqlite `$db = new SPDO('sqlite:my/path/database.db');`.
mysql `$db = new SPDO('mysql:host=localhost;dbname=database', $user, $passwd);`.


### Methods

Execute SQL string `$sql`. `$bind` - array (if it is a string, value is converted to an array) of parameter identifier, for a prepared statement using named placeholders or question mark placeholders. If argument `$fetchAll` is `true` apply `PDOStatement::fetchAll`, else `PDOStatement::fetch`
```
executeQuery($sql, $bind=null, $fetchAll=true)
```

Execute SQL string `$sql`. Return one row data. 
```
executeOne($sql, $bind=null)
```

Execute SQL string. Return all data.
```
executeAll($sql, $bind=null)
```

Get information of table, `$table` - table name. Return array with full detail.
```
tableInfo($table) 
```

Simplified method SELECT data. `$fields` - string, selected fields of `*`. `$table` - string, table name. `$where` - string, filter. `$bind` - array, parameter identifier. `$fetchAll` - bool. 
```
select($fields, $table, $where="", $bind=null, $fetchAll=true)
```

Simplified method INSERT data.
```
insert($table, array $columnData)
```

Simplified method DELETE data.
```
delete($table, $where, $bind=null)
```

Simplified method DELETE data.
```
update($table, array $columnData, $where, $bind=null)
```

Get error info. `$row` it's type info, can take - `error`, `sql` or `bind` and return string, default false return array with all types.
```
getError($row=false)
```


### Use
```
include('db/SPDO.php');

// create instance
$db = new SPDO('sqlite:my/path/database.db');

// you can check to table exist
if(!$db->tableInfo('table_name'))
    die('Table "table_name" not exists!');
    
// Show error text if there are
if($error = $db->getError()){
    echo 'Driver error: '.$error['error'].'<br />';
    echo 'SQL: '.$error['sql'].'<br />';
}
```

### Examples with are different placeholders
```
$result = $db->executeAll('SELECT * FROM table_name WHERE id=:id',['id'=>5]);
// equally
$result = $db->executeAll('SELECT * FROM table_name WHERE id=?',5);
// equally
$result = $db->select('*','records','id=?',5);
// equally
$result = $db->select('id, title, description','table_name','id=:id',[':id'=>5], false);
```

### Example INSERT
```
// issue data
$insData = [
    'title' => 'Insert Record',
    'description' => 'Insert record description!'
];
$result = $db->insert('table_name', $insData); // run operation
$lastInsertId = $db->lastInsertId(); // get insert id
```

### Example UPDATE
```
// data to update
$updData = [
    'title' => 'Insert 2 Snippet',
    'description' => 'Insert description snippet!'
];
$result = $db->update('table_name',$updData,'id=10');
$result = $db->update('table_name',$updData,'id=?','10'); // equally with previous
// generated: UPDATE table_name SET title=?, description=? WHERE id=?;


// BEWARE! peculiar of the method `update`: named placeholders always convert to question mark placeholders. 
// Use question mark placeholders to avoid errors
$result = $db->update('table_name',$updData,'id=:id',[':id'=>10]);
```

### Example DELETE
```
$result = $db->delete('table_name','id=:id',[':id'=>'10']);
```



### You also can use all the methods PDO/PDOStatement directly, like:
```
    $sql = "
        SELECT r.id, r.title, r.description, u.name, u.email
        FROM records r
        LEFT JOIN users u ON (u.id = r.iduser)
        WHERE r.link=:link";

    $stat = $db->prepare($sql);
    $stat->bindParam(':link',$link,PDO::PARAM_STR);
    $stat->execute();
    $contentRec = $stat->fetch();
```


