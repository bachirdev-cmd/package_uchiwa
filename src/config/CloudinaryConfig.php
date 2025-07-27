<?php

namespace AppDAF\CONFIG;

use AppDAF\CONFIG\INTERFACES\CloudConfigInterface;

class CloudinaryConfig implements CloudConfigInterface
{
    private array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/cloudinary.php';
    }

    public function getCloudName(): string
    {
        return $this->config['cloud_name'];
    }

    public function getApiKey(): string
    {
        return $this->config['api_key'];
    }

    public function getApiSecret(): string
    {
        return $this->config['api_secret'];
    }
}
