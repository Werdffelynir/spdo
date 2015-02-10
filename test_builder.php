<?php
# .
require_once('./test_config.php');

use \db\SBuilder;




# .
$mdb = new SBuilder('dbMySql');


/*
$mdb->select('*')
    ->from('pages')
    ->where('id <= :id AND public = :pb')
    ->condition([
        ':id'=>1,
        ':pb'=>1,
    ]);
$res = $mdb->execute()->fetchAll();
*/

/*
$mdb->select('*')
    ->from('pages')
    ->where('id >= :id AND public = :pb')
    ->prepare()
        ->bind(':id', '1', \PDO::PARAM_INT)
        ->bind(':pb', '1', \PDO::PARAM_INT);
$res = $mdb->execute()->fetchAll();
*/

/*
$mdb->select('p.title, p.content, u.name')
    ->from('pages p')
    ->leftJoin('users u','u.id = p.userid')
    ->where('p.public = :pb AND u.id = :uid')
    ->condition([
        ':pb'=>1,
        ':uid'=>2,
    ]);
$res = $mdb->execute()->fetchAll();
*/

/*
$mdb->insert('pages')
    ->set([
        'title'=>'Insert title',
        'content'=>'Insert content',
        'userid'=>'1',
    ]);
$insertId = $mdb->execute();
*/

/*
$mdb->insertInto('pages')
    ->values([
        'title'=>'Insert into title',
        'content'=>'Insert into content',
        'userid'=>'1',
    ])
    ->execute();
*/

/*
$mdb->update('pages')
    ->set([
        'title'=>'Update builder title',
        'content'=>'Update builder content',
        'userid'=>'2',
    ])
    ->where('id = :id')
    ->condition([
        ':id' => 6
    ]);
$updateRows = $mdb->execute();
*/

/*
$spdo = $mdb->spdo;
$insertId = $spdo->queryInsert('pages',[
    'title' => 'Some title',
    'content' => 'Some data',
    'userid' => 2,
]);
*/

/*
$mdb->delete('pages')
    ->where('id = :id')
    ->condition([
        ':id' => 7
    ]);
//$updateRows = $mdb->execute();
*/

/*
$deletesRows = $mdb->delete('pages')
    ->where('id = :id')
    ->execute([
        ':id' => 8
    ]);
var_dump($deletesRows);
*/

/**
 * использование bindParam() для этого нужно после построения запроса использувать prepare() без параметров,
 * таким образом будет создан обект PDOStatement. Если упустить prepare() и дальше использовать bind() все
 * значения уйдут как параметры в execute(), в место ожидаемого bindParam()
 *
$mdb->select('*')
    ->from('pages')
    ->where('public = :pb AND userid = :uid')
    ->prepare()
        ->bind(':pb', 1, \PDO::PARAM_INT)
        ->bind(':uid', 2, \PDO::PARAM_INT);
$res = $mdb->execute()->fetchAll();
*/

/*
$res = $mdb
    ->select('*')
    ->from('pages')
    ->where('public = :pb AND userid = :uid')
    ->orderBy('title','ASC')
    ->prepare()
    ->bind(':pb', 1, \PDO::PARAM_INT)
    ->bind(':uid', 2, \PDO::PARAM_INT)
    ->executeAll();
*/

/*
$mdb->select('*')
    ->from('pages')
    ->where('public = :pb AND userid = :uid')
    ->orderBy('title','ASC');

$res = $mdb->executeAll([
    ':pb'=>1,
    ':uid'=>2,
]);
*/

/*
$mdb->select('*')
    ->from('pages')
    ->where('public = :pb AND userid = :uid')
    ->orderBy('title','ASC');

$mdb->condition([
    ':pb'=>1,
    ':uid'=>2,
]);

$res = $mdb->executeAll();
*/

var_dump($res);

//var_dump($mdb->getSql());


/*
$mdb->select('*')
    ->from('pages')
    ->where('id = :id AND public = :pb')
    ->bind(':id', $id, \PDO::PARAM_INT)
    ->bind(':pb', $public, \PDO::PARAM_INT);

$mdb->select('title, content, createrecord')
    ->from('pages')
    ->where('id = :id AND public = :pb')
    ->binds([
            ':id'=>$id,
            ':pb'=>$public,
        ]);

$mdb->select('p.title',
            'p.content',
            'p.createrecord',
            'u.author')
    ->from('pages p')
    ->leftJoin('users u', 'u.id = p.userid')
    ->where('p.id = ?')
    ->and('p.public = ?')
    ->orderBy('p.id DESC')
    ->orderBy('p.id','DESC');

$mdb->bind(':id', $id, \PDO::PARAM_INT)
    ->bind(':id', $id, \PDO::PARAM_INT);

$result = $mdb->execOne();
$result = $mdb->execAll();
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



