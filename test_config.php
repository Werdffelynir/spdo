<?php
/**
 * Simple PDO wrapper
 *
 * @link https://github.com/Werdffelynir/spdo
 * @author OL Werdffelynir <werdffelynir@gmail.com>
 * @created 08.02.15
 * @license  GNU AGPLv3 https://gnu.org/licenses/agpl.html
 */

/**
 * Mini doc and examples
 */


# .include handle class
require_once('./db/SPDO.php');
require_once('./db/SBuilder.php');

use \db\SPDO;


/*
# .Назначения:
-

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
