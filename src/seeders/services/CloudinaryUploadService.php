<?php

namespace AppDAF\SEEDERS\SERVICES;

use AppDAF\CONFIG\INTERFACES\CloudConfigInterface;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryUploadService
{
    private Cloudinary $cloudinary;

    public function __construct(CloudConfigInterface $cloudConfig)
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => $cloudConfig->getCloudName(),
                'api_key'    => $cloudConfig->getApiKey(),
                'api_secret' => $cloudConfig->getApiSecret(),
            ],
            'url' => ['secure' => true]
        ]);

        $this->cloudinary = new Cloudinary(Configuration::instance());
    }

    public function uploadImage(string $imagePath, string $folder): string
    {
        if (!file_exists($imagePath)) {
            throw new \InvalidArgumentException("Image file not found: {$imagePath}");
        }

        $result = $this->cloudinary->uploadApi()->upload($imagePath, ['folder' => $folder]);
        return $result['secure_url'];
    }

    public function uploadCniImages(string $rectoPath, string $versoPath): array
    {
        return [
            'recto' => $this->uploadImage($rectoPath, 'cni/recto'),
            'verso' => $this->uploadImage($versoPath, 'cni/verso')
        ];
    }
}
