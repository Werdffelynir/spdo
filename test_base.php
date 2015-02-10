<?php
# .
require_once('./test_config.php');

use \db\SPDO;


# .подключиться по имени конфигурации базы данных (connectName), через конструктор
$spdo = new SPDO('dbMySql');


# .иной способ, так же возвращает экземпляр класса
//$spdo = new SPDO();
//$spdo->initConnect('dbMySql');
# или может принимать дополнительные параметры. Этот способ удобней если работа идет с несколькими подключениями
# $spdo = $spdo->initConnect('dbMySql', 'SELECT * FROM pages')->fetchAll();
# в этом случае возвращает заряженый \PDOStatement


# .третий способ через статический метод
//$spdo = SPDO::initStaticConnect('dbMySql');
# также может принимать дополнительные параметры, аналогично initConnect()
# $spdo = SPDO::initStaticConnect('dbMySql', 'SELECT * FROM pages')->fetchAll();


# .  .  .  .  .  .  .  .  .  .  .  .основные методы.  .  .  .  .  .  .  .  .  .  .  .  . #


# .прямые запросы
/*
$sql = "INSERT pages SET title='Some page', content='Some text'";
$crw = $spdo->dbh()->exec($sql);
$crw = SPDO::initStaticConnect('dbMySql')->dbh()->exec($sql); //or with new connectName
*/

/*
$sth = $spdo->dbh()->query("SELECT * FROM pages WHERE id = 1");
$record = $sth->fetch();
*/

/*
$sth = $spdo->dbh()->prepare('SELECT * FROM pages WHERE id <= :id AND public = :bc');
$sth->bindValue(':id', 5, \PDO::PARAM_INT);
$sth->bindValue(':bc', 1, \PDO::PARAM_INT);
$sth->execute();
$record = $sth->fetchAll();
*/

/*
$sth = $spdo->dbh()->prepare("INSERT pages SET title=?, content=?");
$sth->execute(['Some page 2','Some text 2']);
$crw = $sth->rowCount();
$iid = $spdo->dbh()->lastInsertId();
*/




# .
# используется как базовый метод для всех запросов.
//$record = $spdo->querySql('SELECT * FROM pages WHERE id = ?', [1])->fetch();


# .
//$records = $spdo->querySelect('*','pages')->fetchAll();


# .
//$insertId = $spdo->queryInsert('pages',
//    [
//        'title' => 'New Title',
//        'content' => 'New Content',
//    ]
//);


# .
//$numUpdatesRecords = $spdo->queryUpdate('pages',
//    [
//        'title' => 'Change Title',
//        'content' => 'Change Content',
//    ],
//    'id = ?', [ 3 ]
//);


# .
//$numDeletesRecords = $spdo->queryDelete('pages','id = ?', [ 3 ]);


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



# .



