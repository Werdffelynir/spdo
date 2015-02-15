<?php
# .
require_once('./config.php');

use \spdo\SPDO;


/*
Инициализация одного екземпляра дает возможность одновременно иметь соединение только с
одним подключением, возможно переключаться между подключениями методом ->openConnect('other_db');

# подключиться по имени конфигурации базы данных (connectName), через конструктор
//$spdo = new SPDO('dbMySql');

# .подключиться по умрлчанию, берет конфигурацию с именем connectName "db"
$spdo = new SPDO();
//анаоргично
$spdo = SPDO::open();

//подключить db c конфигурацию с именем connectName "dbLite"
$db = $spdo->openConnect('dbLite');

$result = $db->querySql('SELECT * FROM article')->fetchAll();
var_dump($result);

//подключиться на другую db
$db = $spdo->openConnect('db');
$result = $db->querySql('SELECT * FROM pages')->fetchAll();
var_dump($result);
*/



/*
Отдльные подключения
$dbMySql = SPDO::open('dbMySql');
$dbLite = SPDO::open('dbLite');

$result1 = $dbMySql
    ->querySql('SELECT * FROM pages')
    ->fetchAll();

$result2 = $dbLite
    ->querySql('SELECT * FROM article')
    ->fetchAll();

var_dump($result1,$result2);

*/



# .  .  .  .  .  .  .  .  .  .  .  .основные методы.  .  .  .  .  .  .  .  .  .  .  .  . #

$spdo = new SPDO();

# .прямые запросы
/*
$title = 'ConFoo 2015 - Become a Master';
$content = 'We want you to learn as much as possible during the three days of conference. We do that through quality and variety of both content and speakers, as well as creating a fun and friendly atmosphere.';

$sql = "INSERT pages SET title='$title', content='$content'";
$result = $spdo
    ->dbh()
    ->exec($sql);
*/



/*
 * $spdo = new SPDO();
$sth = $spdo
    ->dbh()
    ->query("SELECT * FROM pages WHERE id = 1");
$record = $sth->fetch();
*/



/*
$sth = $spdo
    ->dbh()
    ->prepare('SELECT * FROM pages WHERE id <= :id AND public = :bc');
$sth->bindValue(':id', 5, \PDO::PARAM_INT);
$sth->bindValue(':bc', 1, \PDO::PARAM_INT);
$sth->execute();
$record = $sth->fetchAll();
*/



/*
$sth = $spdo
    ->dbh()
    ->prepare("INSERT pages SET title=?, content=?");
$sth->execute(
    [
        'Some page 2',
        'Some text 2'
    ]);
$crw = $sth->rowCount();
$iid = $spdo->dbh()->lastInsertId();
*/



# .
# используется как базовый метод для всех запросов.
/*
$record = $spdo
    ->querySql(
        'SELECT * FROM pages WHERE id = ? AND public=?',
        [
            "1",
            "1"
        ]
    )
    ->fetch();

var_dump($record);
*/



# .
/*
$records = $spdo->querySelect('*','pages')->fetchAll();
var_dump($records);
*/



# .
/*
$sth = $spdo->querySelect('*','pages','userid IS NULL');
$records = $sth->fetchAll();
var_dump($records);
*/



# .
/*
$insertId = $spdo->queryInsert('pages',
    [
        'title' => 'Taking place on the south coast of England in Summer',
        'content' => 'England in Summer 2015, where community members from around the world will come together to learn and share information about the latest trends and technologies in professional PHP development.',
    ]
);
var_dump($insertId);
*/



/**/
# .
$numUpdatesRecords = $spdo->queryUpdate('pages',
    [
        'title' => 'Change Title',
        'content' => 'Change Content',
    ],
    'id = :id',
    [
        11
    ]
);
var_dump($numUpdatesRecords);




/*
# .
$numUpdatesRecords = $spdo->queryUpdate(
    'pages',
    [
        'userid' => 175
    ],
    'userid IS NULL'
);
var_dump($numUpdatesRecords);
*/



# .
/*
$numDeletesRecords = $spdo->queryDelete('pages','id = ?',
    [
        3
    ]);
var_dump($numDeletesRecords);
*/




# .



# .



# .



# .



# .



# .



# .



# .



# .



# .



# .



# .



# .



# .



# .



