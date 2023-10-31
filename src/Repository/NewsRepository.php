<?php

namespace DummyNewsSite\Repository;

use DummyNewsSite\Model\News;
use PDO;

class NewsRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getUserNews(int $userId): array
    {
        $sql = <<<SQL
SELECT id, title, content
FROM news
WHERE user_id = :userId
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $rows = $stmt->fetchAll();

        $news = [];
        foreach ($rows as $row) {
            $news[] = new News($row['id'], htmlspecialchars_decode($row['title']), htmlspecialchars_decode($row['content']));
        }

        return $news;
    }

    public function delete(int $newsId): void
    {
        $sql = <<<SQL
DELETE FROM news
WHERE id = '$newsId'
SQL;

        $this->connection->query($sql);
    }

    public function create(int $userId, string $title, string $description): void
    {
        $sql = <<<SQL
INSERT INTO news (user_id, title, content)
VALUES ('$userId', '$title', '$description')
SQL;

        $this->connection->query($sql);
    }

    public function edit(int $newsId, string $title, string $description): void
    {
        $sql = <<<SQL
UPDATE news
SET title = '$title', content = '$description'
WHERE id = '$newsId'
SQL;

        $this->connection->query($sql);
    }

}