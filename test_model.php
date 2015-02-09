<?php
# .
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
//$crw = $spdo->dbh()->exec("INSERT pages SET title='Some page', content='Some text'");
$sth = $spdo->dbh()->prepare("INSERT pages SET title=?, content=?");
$sth->execute(['Some page 2','Some text 2']);
$crw = $sth->rowCount();
$iid = $spdo->dbh()->lastInsertId();
var_dump($iid);

//SPDO::initStaticConnect('dbMySql')->dbh()->exec('');


# .
# используется как базовый метод для всех запросов.
//$record = $spdo->querySql('SELECT * FROM pages WHERE id = ?', [1])->fetch();


# .
//$records = $spdo->select('*','pages')->fetchAll();


# .
//$insertId = $spdo->insert('pages',
//    [
//        'title' => 'New Title',
//        'content' => 'New Content',
//    ]
//);


# .
//$numUpdatesRecords = $spdo->update('pages',
//    [
//        'title' => 'Change Title',
//        'content' => 'Change Content',
//    ],
//    'id = ?', [ 3 ]
//);


# .
//$numDeletesRecords = $spdo->delete('pages','id = ?', [ 3 ]);


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



