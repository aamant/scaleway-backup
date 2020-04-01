<?php
/**
 * Config
 *
 * @copyright Copyright © 2020 Arnaud Amant. All rights reserved.
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace Aamant\ScalewayBackup;

/**
 * Class Config
 *
 * @package Aamant\ScalewayBackup
 *
 */
class Config
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getOrganisation()
    {
        return $this->data['organisation'];
    }

    public function getSecret()
    {
        return $this->data['secret'];
    }

    public function getZone()
    {
        return $this->data['zone'];
    }

    public function getInstance()
    {
        return $this->data['instance'];
    }

    public function getDuration()
    {
        return $this->data['duration'];
    }
}