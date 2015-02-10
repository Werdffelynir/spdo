<?php
# .
require_once('./test_config.php');

use \db\SBuilder;




# .
$mdb = new SBuilder('dbMySql');

var_dump($mdb);

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



