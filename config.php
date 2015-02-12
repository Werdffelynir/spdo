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
require_once('./spdo/SPDO.php');
require_once('./spdo/SBuilder.php');
require_once('./spdo/SModel.php');


# .this configuration see and use all instances created after
\spdo\SPDO::setConfigure(
    [
        'db'=>
            [
                'dns'=>'mysql:host=127.0.0.1;dbname=spdo',
                'username'=>'root',
                'password'=>''
            ],
        'dbMySql'=>
            [
                'dns'=>'mysql:host=localhost;dbname=spdo_test',
                'username'=>'root'
            ],
        'dbLite'=>
            [
                'dns'=>'sqlite:../database/spdo.sqlite'
            ]
    ]
);

