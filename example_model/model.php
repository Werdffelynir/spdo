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


$model = new Pages('dbMySql');


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
# -> comboSelect()

$result = $model->comboSelect('*');


# ->
$result = $model->comboSelect('*','public = 1');


# ->
$result = $model->comboSelect('*','public = ?',[1]);



# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> comboInsert()

$result = $model->comboInsert(
    [
        'title' => 'Some Title',
        'content' => 'Some Content Data',
    ]
);




# -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   -
# -> comboUpdate()

$result = $model->comboUpdate(
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
# -> comboDelete()

$result = $model->comboDelete(
    'id = ?',
    [
        13
    ]
);











