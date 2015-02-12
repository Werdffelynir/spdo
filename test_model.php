<?php
# .
require_once('./test_config.php');
require_once('./models/Pages.php');

use \models\Pages;
use \db\SModel;

# .прим констролера

# .подключиться по имени конфигурации базы данных (connectName), через конструктор
//$pages = new Pages('dbMySql');

SModel::initStaticConnect('dbMySql');

//$model = new Pages();

//$model = Pages::initStaticConnect('dbMySql');

$model = Pages::model();
var_dump($model);

//$allPages = $pages->allPages();
//var_dump($allPages);


//$userPages = $pages->allPagesByUserId(2);
//var_dump($userPages);









