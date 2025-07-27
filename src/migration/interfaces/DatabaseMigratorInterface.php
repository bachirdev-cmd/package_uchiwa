<?php

namespace AppDAF\MIGRATION\INTERFACES;

interface DatabaseMigratorInterface
{
    public function createDatabase(string $dbName): bool;
    public function databaseExists(string $dbName): bool;
    public function createTables(): void;
    public function getDriver(): string;
}
