<?php

# .include main class to connection
require_once('./db/SPDO.php');

use \db\SPDO;

/*
# .Назначения:


# .Требования:
- PHP 5.4 +
- Раширение PHP_PDO. Просмотреть PHP_PDO список доступных драйверов можно так:
print_r(\PDO::getAvailableDrivers());

# .Рекомендации:
- использувать базу данных с поддержкой transactions. Например для MySQL InnoDB
*/



# .this configuration see and use all instances created after
SPDO::setConfigure(
    [
        'dbLite'=>
            [
                'dns'=>'sqlite:../database/spdo.sqlite'
            ],
        'dbMySql'=>
            [
                'dns'=>'mysql:host=127.0.0.1;dbname=spdo',
                'user'=>'root',
                'password'=>''
            ]
    ]
);




require_once('./test_model.php');