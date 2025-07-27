<?php

namespace AppDAF\CONFIG\INTERFACES;

interface CloudConfigInterface
{
    public function getCloudName(): string;
    public function getApiKey(): string;
    public function getApiSecret(): string;
}
