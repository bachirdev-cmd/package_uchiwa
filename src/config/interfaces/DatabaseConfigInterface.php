<?php

namespace AppDAF\CONFIG\INTERFACES;

interface DatabaseConfigInterface
{
    public function getHost(): string;
    public function getPort(): int;
    public function getDatabase(): string;
    public function getUsername(): string;
    public function getPassword(): string;
    public function getCharset(): string;
}
