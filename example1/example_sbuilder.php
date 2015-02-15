<?php

/**
SBuilder::select()
SBuilder::insert()
SBuilder::insertInto()
SBuilder::update()
SBuilder::delete()
SBuilder::from()
SBuilder::where()
SBuilder::join()
SBuilder::leftJoin()
SBuilder::innerJoin()
SBuilder::outerJoin()
SBuilder::rightJoin()
SBuilder::orderBy()
SBuilder::groupBy()
SBuilder::set()
SBuilder::values()

SBuilder::clear()
SBuilder::getLastBuildSql()
SBuilder::condition()
SBuilder::prepare()
SBuilder::execute()
SBuilder::executeOne()
SBuilder::executeAll()
SBuilder::lastInsertId()
SBuilder::rowCount()
 */

# .
require_once('./config.php');

use \spdo\SPDO;
use \spdo\SBuilder;



//$db = SBuilder::open('db'); // wrong, its returned instance SPDO
$db = new SBuilder('db');



# .выборка без включения параметров
/*
$db->select('*')
    ->from('pages')
    ->where('id = 1');

$res = $db->executeOne();
*/


# .параметры можно вносить в метотод ->execute(array $params) в виде массива.
/*
$db->select('*')
    ->from('pages')
    ->where('id = :id');

$res = $db->executeOne([
    ':id' => 2
]);
*/


# .а также параметры можно вносить через метотод ->condition(array $params), отличие от использования
# execute() в том что метод добавляет параметы в общий стак для будущего выполнения.
/*
$db->select('*')
    ->from('pages')
    ->where('id = ?');
$db->condition([
    '8'
]);
$res = $db->executeOne();
*/
/* еще примерчик
$db->select('*')
    ->from('pages')
    ->where('id >= :id AND public = :pb')
    ->condition([
        ':id'=>5,
        ':pb'=>1,
    ]);
$res = $db->executeAll();
*/
/* аналогично
$db->select('*')
    ->from('pages')
    ->where('id >= ? AND public = ?');

$db->condition([5,1]);

$sql = $db->getLastBuildSql();
$res = $db->executeOne();
*/



# .Использование prepare(), bind(), ->prepare() - подготавлевает запрос создает \PDOStatement,
# \PDOStatement необходим для использования метода ->bind()
/*
$db->select('*')
    ->from('pages')
    ->where('id >= :id AND public = :pb')
    ->prepare()                             // подготавлевает запрос, создавая внутрений \PDOStatement
        ->bind(':id', 6, \PDO::PARAM_INT)   // только при созданом \PDOStatement
        ->bind(':pb', 1, \PDO::PARAM_INT);
$res = $db->executeAll();
*/



# .Использование leftJoin() condition(). execute() возвращает \PDOStatement, в различии от
# executeOne() кторый использует fetch(); и
# executeAll() использует fetchAll();
/*
$db->select('p.title, p.content, u.name')
    ->from('pages p')
    ->leftJoin('users u','u.id = p.userid')
    ->where('p.public = :pb AND u.id = :uid')
    ->condition([
        ':pb'   =>  1,
        ':uid'  =>  2,
    ]);
$res = $db->execute()->fetchAll();
var_dump($res);
*/

/*
$res = $db->select('*')
    ->from('pages')
    ->where('public = :pb AND userid = :uid')
    ->orderBy('title','ASC')
    ->executeAll(
        [
            ':pb'   =>  1,
            ':uid'  =>  2,
        ]
    );
var_dump($res);
*/


# .INSERT
# Втавка записей возможно производить друмя методами. insert() и insertInto()
# различие в синтаксисе постоении строки SQL.
/*
$db->insert('pages')
    ->set([
        'title'=>'Insert 175 title',
        'content'=>'Insert 175 content',
        'userid'=>'175',
    ]);
*/
// or alternative syntax
/*
$db->insertInto('pages')
    ->values([
        'title'=>'Insert 175 title',
        'content'=>'Insert 175 content',
        'userid'=>'175',
    ]);

$buildSql = $db->getLastBuildSql();
$insertId = $db->execute();
var_dump($buildSql,$insertId);
*/



# .UPDATE
/*
$db->update('pages')
    ->set([
        'title'=>'update title',
        'content'=>'update content',
        'userid'=>'175',
    ])
    ->where("id = ?")
    ->condition([
        14
    ]);

$buildSql = $db->getLastBuildSql();
var_dump($buildSql);

$countColumns = $db->execute();
var_dump($countColumns);
*/





# .DELETE
/*
$db->delete('pages')
    ->where('id = :id')
    ->condition([
        ':id' => 13
    ]);
var_dump($db->getLastBuildSql());
$deletesRows = $db->execute();
var_dump($deletesRows);
*/

/*
$deletesRows = $db->delete('pages')
    ->where('id = :id')
    ->execute([
        ':id' => 15
    ]);
var_dump($deletesRows);
*/





# .



# .



# .



# .



# .



