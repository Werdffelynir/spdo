<?php
/**
 * Simple PDO wrapper
 *
 * @link https://github.com/Werdffelynir/spdo
 * @author OL Werdffelynir <werdffelynir@gmail.com>
 * @created 08.02.15
 * @license  GNU AGPLv3 https://gnu.org/licenses/agpl.html
 */


# -> Include handle class
require_once('../spdo/SPDO.php');
require_once('../spdo/SBuilder.php');
require_once('../spdo/SModel.php');


# -> Configuration connect with databases, all instances created after by self name.
# -> 'db': its first and default connectName
\spdo\SPDO::setConfigure(
    [
        'db'=>[
            'dsn' => 'mysql:host=localhost;dbname=spdo',
            'username' => 'root',
            'password' => '',
        ],
        'lite'=>[
            'dsn'=>'sqlite:database/spdo.sqlite'
        ],
    ]
);









