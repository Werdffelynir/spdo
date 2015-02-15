<?php

require_once('./config.php');
require_once('./models/Pages.php');
require_once('./models/Users.php');

use \spdo\SModel;
use \models\Pages;
use \models\Users;




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> Connect


//$model = Pages::open('db'); // wrong, its returned instance SPDO


$model = new Pages('db');


# -> Static connect
$model = Pages::model();


# -> Static connect from SModel
$model = SModel::model('\models\Pages');


# -> Change database
$model->openConnect('otherConnectName');


# ->
$connectName = $model->getConnectName();
$modelPages = new Pages();
$modelUsers = new Users();

var_dump(
    $modelPages->all(),
    $modelUsers->byId(1)
);



# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> tableSelect()

$result = $model->tableSelect('*');


# ->
$result = $model->tableSelect('*','public = 1');


# ->
$result = $model->tableSelect('*','public = ?',[1]);



# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> tableInsert()

$result = $model->tableInsert(
    [
        'title' => 'Some Title',
        'content' => 'Some Content Data',
    ]
);




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> tableUpdate()

$result = $model->tableUpdate(
    [
        'title' => 'Some Title',
        'content' => 'Some Content Data',
    ],
    'id = ?',
    [
        13
    ]
);




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> tableDelete()

$result = $model->tableDelete(
    'id = ?',
    [
        13
    ]
);





# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# ->

$result = $model->tableSelect('*')->fetchAll();

$result = $model->tableCount();

$result = $model->tableCount('public = 1');

$result = $model->getById(1);

$result = $model->getOneByAttr('userid',175);

$result = $model->getAllByAttr('userid',175);









