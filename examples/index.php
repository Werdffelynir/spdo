<?php

include('../src/SPDO.php');


$db = new Db('sqlite:prod.sqlite');


//$result = $db->executeAll('SELECT * FROM snippets WHERE id=:id',['id'=>5]);


//$result = $db->executeAll('SELECT * FROM snippets WHERE id=?','6');


//$result = $db->select('*','snippets','id=?','5');


//$result = $db->tableInfo('snippets',['link','tags','title','description','iduser','idcategory','idsubcategory']);



//var_dump($db->tableInfo('snippets'));



/*
$insData = [
    'title' => 'Insert 2 Snippet',
    'description' => 'Insert description snippet!'
];
$result = $db->insert('snippets',$insData);
$lastInsertId = $db->lastInsertId();
*/


/*
$updData = [
    'title' => 'Insert 2 Snippet',
    'description' => 'Insert description snippet!'
];
$result = $db->update('snippets',$updData,'id=10');
# .UPDATE snippets SET title=:title, description=:description WHERE id=10;
var_dump($result);


$updData = [
    'title' => 'Insert 2 Snippet',
    'description' => 'Insert description snippet!'
];
$result = $db->update('snippets',$updData,'id=:id',[':id'=>10]);
# .UPDATE snippets SET title=:title, description=:description WHERE id=:id;
var_dump($result);


$updData = [
    'title' => 'Insert 2 Snippet',
    'description' => 'Insert description snippet!'
];
$result = $db->update('snippets',$updData,'id=?','10');
# .UPDATE snippets SET title=?, description=? WHERE id=?;
var_dump($result);


$updData = [
    'title' => 'Insert 2 Snippet',
    'description' => 'Insert description snippet!'
];
$result = $db->update('snippets',$updData,'id=? AND active=?',['10','1']);
# .UPDATE snippets SET title=?, description=? WHERE id=? AND active=?;
var_dump($result);
*/



//$result = $db->delete('snippets','id=:id',[':id'=>'10']);
# .

//$result = $db->select('*','snippets');


# .
//$result = $db->select('id, title, description','snippets','id=:id',[':id'=>9], false);

# .
//var_dump($result);



if(isset($_GET['recid']) && ($recid = $_GET['recid'])){

    $rec = $db->select('id, title, description','snippets','id=:id',[':id'=>(int)$recid], false);
    echo "<div><a href=\"/index.php\">Beak</a></div>";
    echo "<div>";
    echo "<h2><a href=\"/index.php?recid={$rec['id']} \">{$rec['title']}</a></h2>";
    echo "<div>{$rec['description']}</div>";
    echo "</div>";

}else{
    foreach ($db->select('id, title, description','snippets','active=?',1) as $rec) {
        echo "<div>";
        echo "<h3><a href=\"/index.php?recid={$rec['id']} \">{$rec['title']}</h3>";
        echo "</div>";
    }
}





# .with error
//$db->select('some, next','snippets','id=:id',[':id'=>10]);


if($error = $db->getError()){
    echo 'Driver error: '.$error['error'].'<br />';
    echo 'SQL: '.$error['sql'].'<br />';
}
