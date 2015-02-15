<?php

/**
 * Simple PDO wrapper
 * Mini doc and examples
 * Base use
 */

# -> Common connection settings
require_once('./config.php');

use \spdo\SBuilder;



# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> Connection

//$db = SBuilder::open('db'); // wrong, its returned instance SPDO

//$db = new SBuilder('db');

$db = new SBuilder();



# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> Выборка без включения параметров

/**/
$db->select('*')
    ->from('pages')
    ->where('id = 1');

$result = $db->executeOne();



# -> Параметры можно вносить в метотод ->execute(array $params) [executeOne(), executeAll()] в виде массива.
/**/
$db->select('*')
    ->from('pages')
    ->where('id = :id');

$result = $db->executeOne([
    ':id' => 2
]);





# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> Также параметры можно вносить через метотод ->condition(array $params), отличие от использования
# execute() в том что метод добавляет параметы в общий стак для будущего выполнения.

/**/
$db->select('*')
    ->from('pages')
    ->where('id = ?');

$db->condition([
    '8'
]);

$result = $db->executeOne();



# -> еще примерчик
$db->select('*')
    ->from('pages')
    ->where('id >= :id AND public = :pb')
    ->condition([
        ':id'=>5,
        ':pb'=>1,
    ]);

$result = $db->executeAll();



# -> аналогично
$db->select('*')
    ->from('pages')
    ->where('id >= ? AND public = ?');

$db->condition([5, 1]);

$sql    = $db->getLastBuildSql();
$result = $db->executeOne();



# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> Использование prepare(), bind(), ->prepare() - подготавлевает запрос создает \PDOStatement,
# \PDOStatement необходим для использования метода ->bind()

/**/
$db->select('*')
    ->from('pages')
    ->where('id >= :id AND public = :pb')
    ->prepare()                             // подготавлевает запрос, создавая внутри класса \PDOStatement
        ->bind(':id', 6, \PDO::PARAM_INT)   // только при созданом \PDOStatement
        ->bind(':pb', 1, \PDO::PARAM_INT);

$result = $db->executeAll();






# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> Использование leftJoin() condition(). execute() возвращает \PDOStatement, в различии от
# executeOne() кторый использует fetch(); и
# executeAll() использует fetchAll();

/**/
$db->select('p.title, p.content, u.name')
    ->from('pages p')
    ->leftJoin('users u','u.id = p.userid')
    ->where('p.public = :pb AND u.id = :uid')
    ->condition(
        [
            ':pb'   =>  1,
            ':uid'  =>  2,
        ]
    );

$result = $db
    ->execute()
    ->fetchAll();



/**/
$result = $db->select('*')
    ->from('pages')
    ->where('public = :pb AND userid = :uid')
    ->orderBy('title','ASC')
    ->executeAll(
        [
            ':pb'   =>  1,
            ':uid'  =>  2,
        ]
    );


# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> INSERT
# Втавка записей возможно производить друмя методами. insert() и insertInto()
# различие в синтаксисе постоении строки SQL.
# insert:
# INSERT table SET ( column_name = column_value, ... );
# insertInto:
# INSERT INTO table (column_name, ...) VALUES (column_value, ...);


/**/
$db->insert('pages')
    ->set([
        'title'=>'Insert 175 title',
        'content'=>'Insert 175 content',
        'userid'=>'175',
    ]);



# -> or alternative syntax
/**/
$db->insertInto('pages')
    ->values([
        'title'=>'Insert 175 title',
        'content'=>'Insert 175 content',
        'userid'=>'175',
    ]);

$buildSql = $db->getLastBuildSql();
$insertId = $db->execute();





# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> UPDATE

/**/
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
$rowCount = $db->execute();





# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> DELETE

/**/
$db->delete('pages')
    ->where('id = :id')
    ->condition([
        ':id' => 13
    ]);

$buildSql = $db->getLastBuildSql();
$rowCount = $db->execute();


/**/
$rowCount = $db->delete('pages')
    ->where('id = :id')
    ->execute([
        ':id' => 15
    ]);

// $buildSql - будет пуст
$buildSql = $db->getLastBuildSql();




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# ->