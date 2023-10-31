<?php

namespace DummyNewsSite\Repository;

use PDO;

class SQLiteConnection
{
    private const PATH_TO_SQLITE_FILE = __DIR__ . '/database.db';

    private ?PDO $pdo = null;

    public function connect(): PDO
    {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:".self::PATH_TO_SQLITE_FILE);
        }

        return $this->pdo;
    }
}