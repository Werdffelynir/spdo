# Simple PDO


This simple PHP PDO wrapper, client for quick use database


## Tested on
- SQLite
- MySQL
- Oracle
- PostgreSQL


## Configuration
```
include('db/SPDO.php');



// sqlite
$db = new SPDO('sqlite:my/path/database.db');



// mysql
$db = new SPDO('mysql:host=localhost;dbname=records', $user, $passwd);



// check if table exist
if(!$db->tableInfo('table_name'))
    die('Table "table_name" not exists!');



// get table info
$result = $db->tableInfo('records');



// SELECT
$result = $db->executeAll('SELECT * FROM table_name WHERE id=:id',['id'=>5]);

// or
$result = $db->executeAll('SELECT * FROM table_name WHERE id=?',5);

// or
$result = $db->select('*','records','id=?',5);

// or
$result = $db->select('id, title, description','table_name','id=:id',[':id'=>5], false);



// INSERT
$insData = [
    'title' => 'Insert Record',
    'description' => 'Insert record description!'
];
$result = $db->insert('table_name',$insData);
$lastInsertId = $db->lastInsertId();



// UPDATE
// always will be generated some like: UPDATE table_name SET title=?, description=? WHERE id=?;
$updData = [
    'title' => 'Insert 2 Snippet',
    'description' => 'Insert description snippet!'
];
$result = $db->update('table_name',$updData,'id=10');

// or
$result = $db->update('table_name',$updData,'id=?','10');

// or
$result = $db->update('table_name',$updData,'id=:id',[':id'=>10]);


// DELETE
$result = $db->delete('table_name','id=:id',[':id'=>'10']);



// select with error
$db->select('some, next','table_name','id=:id',[':id'=>10]);

// Show error text if there are
if($error = $db->getError()){
    echo 'Driver error: '.$error['error'].'<br />';
    echo 'SQL: '.$error['sql'].'<br />';
}


// you can use all the methods PDO directly, like
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


