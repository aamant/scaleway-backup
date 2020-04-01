<?php
/**
 * Client
 *
 * @copyright Copyright © 2020 Arnaud Amant. All rights reserved.
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace Aamant\ScalewayBackup;


class Client
{
    const BASE_URL = 'https://api.scaleway.com/';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var
     */
    protected $last_error;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getClient(): \GuzzleHttp\Client
    {
        if (!$this->guzzle) {
            $this->guzzle = new \GuzzleHttp\Client(['base_uri' => self::BASE_URL]);
        }
        return $this->guzzle;
    }

    /**
     * @return Config
     */
    protected function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return bool
     */
    public function backup(): bool
    {
        try {
            $response = $this->getClient()->request('POST', 'instance/v1/zones/'.$this->getConfig()->getZone().'/servers/'.$this->getConfig()->getInstance().'/action', [
                'headers' => [
                    'X-Auth-Token' => $this->getConfig()->getSecret()
                ],
                'json' => [
                    "action" => "backup"
                ]
            ]);

            return true;
        }
        catch (\GuzzleHttp\Exception\ClientException $exception) {
            $this->last_error = $exception->getMessage();
            return false;
        }
    }

    /**
     * @return \stdClass[]
     */
    public function getImages(): array
    {
        try {
            $response = $this->getClient()->request('GET', 'instance/v1/zones/'.$this->getConfig()->getZone().'/images', [
                'query' => ['organization' => $this->getConfig()->getOrganisation()],
                'headers' => [
                    'X-Auth-Token' => $this->getConfig()->getSecret()
                ]
            ]);
            $data = json_decode($response->getBody());
            return $data->images;
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            echo $exception->getMessage();
            $this->last_error = $exception->getMessage();
            return [];
        }
    }

    /**
     * @param \stdClass $image
     * @return bool
     */
    public function delete(\stdClass $image): bool
    {
        try {
            $this->getClient()->request('DELETE', 'instance/v1/zones/'.$this->getConfig()->getZone().'/images/'.$image->id, [
                'headers' => [
                    'X-Auth-Token' => $this->getConfig()->getSecret()
                ]
            ]);

            $response = $this->getClient()->request('GET', 'instance/v1/zones/'.$this->getConfig()->getZone().'/snapshots', [
                'query' => ['name' => $image->name],
                'headers' => [
                    'X-Auth-Token' => $this->getConfig()->getSecret()
                ]
            ]);

            $data = json_decode($response->getBody());
            foreach ($data->snapshots as $snapshot) {
                $this->getClient()->request('DELETE', 'instance/v1/zones/'.$this->getConfig()->getZone().'/snapshots/'.$snapshot->id, [
                    'headers' => [
                        'X-Auth-Token' => $this->getConfig()->getSecret()
                    ]
                ]);
            }

            return true;
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $this->last_error = $exception->getMessage();
            return false;
        }
    }

    /**
     * @return string
     */
    public function getLastError(): string
    {
        $last = $this->last_error;
        $this->last_error = null;
        return (string)$last;
    }
}