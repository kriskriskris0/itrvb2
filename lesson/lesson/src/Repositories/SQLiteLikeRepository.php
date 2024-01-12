<?php

namespace src\Repositories;

use PDO;
use src\Model\Like;
use src\Model\UUID;

class SQLiteLikeRepository implements LikeRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Like $like): void
    {
        $existingLike = $this->getExistingLike($like->getPostUuid(), $like->getUserUuid());

        if ($existingLike) {
            throw new \RuntimeException('Пользователь уже поставил лайк этой статье.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO likes (uuid, post_uuid, user_uuid) VALUES (:uuid, :post_uuid, :user_uuid)'
        );

        $stmt->execute([
            ':uuid' => $like->getUuid(),
            ':post_uuid' => $like->getPostUuid(),
            ':user_uuid' => $like->getUserUuid(),
        ]);
    }

    public function getByPostUuid(UUID $postUuid): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM likes WHERE post_uuid = :post_uuid'
        );

        $stmt->execute([':post_uuid' => $postUuid]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getExistingLike(UUID $postUuid, UUID $userUuid): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM likes WHERE post_uuid = :post_uuid AND user_uuid = :user_uuid'
        );

        $stmt->execute([
            ':post_uuid' => $postUuid,
            ':user_uuid' => $userUuid,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
