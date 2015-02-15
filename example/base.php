<?php

/**
 * Simple PDO wrapper
 * Mini doc and examples
 * Base use
 */

# -> Common connection settings
require_once('./config.php');

use \spdo\SPDO;


# -> Новый экземпляр создает новое подключение, в конструктор можно указать имя подключения, по умолчанию берет первое из массива конфигурации.
# -> Creates a new instance of a new connection, a designer can specify the name of the connection, the default is the first of the array configuration.
$spdo = new SPDO();
//анаоргично
$spdo = SPDO::open(/*[connect name]*/);


# -> Один экземпляр может иметь соединение только с одной db. Переключение соединения осуществляется новым открытием подключения.
$spdo->openConnect('lite');


# -> Для работы одновременно с несколькими db просто создайте несколько екземпляров
/**/
$dbBase = SPDO::open('db');
$dbLite = SPDO::open('lite');

$result1 = $dbBase
    ->querySql('SELECT * FROM pages')
    ->fetchAll();

$result2 = $dbLite
    ->querySql('SELECT * FROM article')
    ->fetchAll();

//var_dump($result1,$result2);




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> querySql() используется как базовый метод для всех запросов, посредсвом прямого sql запроса.
/**/
$record = $spdo
    ->querySql(
        'SELECT * FROM pages WHERE id = ? AND public=?',
        [
            1,
            1
        ]
    )
    ->fetch();




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> querySelect()
/**/
$records = $spdo->querySelect('*','pages')->fetchAll();


$sth = $spdo->querySelect('*','pages','userid IS NULL');
$records = $sth->fetchAll();





# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> queryInsert()
/**/
$insertId = $spdo->queryInsert('pages',
    [
        'title' => 'Taking place on the south coast of England in Summer',
        'content' => 'England in Summer 2015, where community members from around the world will come together to learn and share information about the latest trends and technologies in professional PHP development.',
    ]
);







# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> queryUpdate()
/**/
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

$numUpdatesRecords = $spdo->queryUpdate('pages',
    [
        'userid' => 175
    ],
    'userid IS NULL'
);




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> queryDelete()
/**/
$numDeletesRecords = $spdo->queryDelete('pages',
    'id = ?',
    [
        3
    ]
);






# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> Прямые запросы методами \PDO



/**/
$title = 'ConFoo 2015 - Become a Master';
$content = 'We want you to learn as much as possible during the three days of conference. We do that through quality and variety of both content and speakers, as well as creating a fun and friendly atmosphere.';

$sql = "INSERT pages SET title='$title', content='$content'";
$result = $spdo
    ->dbh()
    ->exec($sql);



/**/
$sth = $spdo
    ->dbh()
    ->query("SELECT * FROM pages WHERE id = 1");
$record = $sth->fetch();



/**/
$sth = $spdo
    ->dbh()
    ->prepare('SELECT * FROM pages WHERE id <= :id AND public = :bc');
$sth->bindValue(':id', 5, \PDO::PARAM_INT);
$sth->bindValue(':bc', 1, \PDO::PARAM_INT);
$sth->execute();
$record = $sth->fetchAll();



/**/
$sth = $spdo
    ->dbh()
    ->prepare("INSERT pages SET title=?, content=?");

$sth->execute(
    [
        'Some page 2',
        'Some text 2'
    ]);

$rct = $sth->rowCount();
$lid = $spdo->dbh()->lastInsertId();




