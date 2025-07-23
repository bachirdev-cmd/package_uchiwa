<?php
namespace AppDAF\SERVICE;

use AppDAF\CORE\Singleton;

class CloudService extends Singleton
{
    public function generateImageUrl(string $filename): string
    {
        // Simule l'URL générée par un cloud comme Cloudinary
        return 'https://res.cloudinary.com/ton-cloud/image/upload/' . $filename;
    }
}

