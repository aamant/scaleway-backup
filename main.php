<?php
/**
 * @copyright Copyright © 2020 Arnaud Amant. All rights reserved.
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

/**
 * @see https://developers.scaleway.com/en/products/instance/api/#introduction
 * @see https://www.scaleway.com/en/docs/how-to-backup-your-data/#-Deleting-a-Backup-via-the-Scaleway-API
 * @see http://docs.guzzlephp.org/en/5.3/quickstart.html#
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$config = new \Aamant\ScalewayBackup\Config($config);
$client = new \Aamant\ScalewayBackup\Client($config);
$backup = new \Aamant\ScalewayBackup\ScalewayBackup($client, $config);

$backup->backup();
$backup->clean();