<?php
/**
 * @copyright Copyright © 2020 Arnaud Amant. All rights reserved.
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$config = new \Aamant\ScalewayBackup\Config($config);
$client = new \Aamant\ScalewayBackup\Client($config);
$backup = new \Aamant\ScalewayBackup\ScalewayBackup($client, $config);

$backup->backup();
$backup->clean();