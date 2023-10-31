<?php

namespace DummyNewsSite\Repository;

use DummyNewsSite\Model\User;
use PDO;

class LoginRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getUserByUsername(string $username): ?User
    {
        $sql = <<<SQL
SELECT id, username, password
FROM user
WHERE username = :username
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return new User($row['id'], $row['username'], $row['password']);
    }

    public function getUserBySessionId(string $sessionId): ?User
    {
        $sql = <<<SQL
SELECT user.id, user_session.session_id, user.username, user.password
FROM user
JOIN user_session ON user.id = user_session.user_id
WHERE user_session.session_id = :sessionId
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['sessionId' => $sessionId]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return new User($row['id'], $row['username'], $row['password'], $row['session_id']);
    }

    public function insertSession(string $sessionId, int $userId): void
    {
        $sql = <<<SQL
INSERT INTO user_session (session_id, user_id)
VALUES ('$sessionId', '$userId')
SQL;

        $this->connection->exec($sql);
    }

    public function sessionExists(string $sessionId): bool
    {
        $sql = <<<SQL
SELECT EXISTS(SELECT 1 FROM user_session WHERE session_id = '$sessionId')
SQL;

        $row = $this->connection->query($sql)->fetch();

        return $row[0];
    }

    public function deleteSession(string $sessionId): void
    {
        $sql = <<<SQL
DELETE FROM user_session
WHERE session_id = "$sessionId"
SQL;

        $this->connection->exec($sql);
    }

}