<?php
namespace src\Repositories;

use PDO;
use PDOException;
use src\Exceptions\PostIncorrectDataException;
use src\Exceptions\PostNotFoundException;
use src\Model\Post;
use src\Model\UUID;

class PostRepository implements PostsRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function get(UUID $uuid): Post
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new PostNotFoundException("Статья с UUID $uuid не найдена");
        }

        return $this->mapToPost($result);
    }

    public function save(Post $post): void
    {
        $this->validateAuthorExistence($post->getAuthorUuid());

        $stmt = $this->pdo->prepare("INSERT INTO posts (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)");

        try {
            $stmt->execute([
                ':uuid' => $post->getUuid(),
                ':author_uuid' => $post->getAuthorUuid(),
                ':title' => $post->getTitle(),
                ':text' => $post->getText()
            ]);
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Ошибка при сохранении статьи: " . $e->getMessage());
        }
    }

    public function delete(UUID $uuid): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);

        if ($stmt->rowCount() === 0) {
            throw new PostNotFoundException("Статья с UUID $uuid не найдена");
        }
    }

    private function validateAuthorExistence(UUID $authorUuid): void
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $authorUuid]);

        if ($stmt->fetchColumn() === 0) {
            throw new PostIncorrectDataException("Автор с UUID $authorUuid не найден");
        }
    }

    private function mapToPost(array $data): Post
    {
        return new Post($data['uuid'], $data['author_uuid'], $data['title'], $data['text']);
    }
}
