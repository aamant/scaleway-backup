<?php
/**
 * ScalewayBackup
 *
 * @copyright Copyright © 2020 Arnaud Amant. All rights reserved.
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace Aamant\ScalewayBackup;


class ScalewayBackup
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Config
     */
    private $config;

    public function __construct(Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function backup()
    {
        echo "create backup ";
        if (!$this->client->backup()){
            echo "\t\t[ERROR]\n";
            return false;
        }
        echo "\t\t[BACKUPED]\n";
        return true;
    }

    public function clean($force = false)
    {
        $images = $this->client->getImages();
        echo 'number existed images: '.count($images)."\n";
        if (count($images) && $this->client->getLastError()) {
            echo $this->client->getLastError()."\n";
        }
        foreach ($images as $image) {
            $date = new \DateTime($image->creation_date);
            $date->modify('+'.$this->config->getDuration().' days');
            $now = new \DateTime();
            if ($now >= $date || $force) {
                echo "delete image: ";
                echo $image->name;
                if ($this->client->delete($image)) {
                    echo "\t\t[DELETED]\n";
                } else {
                    echo "\t\t[ERROR]\n";
                    echo $this->client->getLastError()."\n";
                }
            }
        }
        return true;
    }
}