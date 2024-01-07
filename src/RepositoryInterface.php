<?php
namespace my;

class CommentsRepository implements CommentsRepositoryInterface {
    public function get(string $uuid): Comment { 
        $query = "SELECT * FROM comments WHERE comment_uuid = :uuid";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':uuid', $uuid);
        $statement->execute();
        $data = $statement->fetch();

        return new Comment($data['comment_uuid'], $data['article_uuid'], $data['author_uuid'], $data['text']);
    }

    public function save(Comment $comment): void {
        $query = "INSERT INTO comments (comment_uuid, article_uuid, author_uuid, text) VALUES (:comment_uuid, :article_uuid, :author_uuid, :text)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':comment_uuid', $comment->getUuid());
        $statement->bindValue(':article_uuid', $comment->getArticleUuid());
        $statement->bindValue(':author_uuid', $comment->getAuthorUuid());
        $statement->bindValue(':text', $comment->getText());
        $statement->execute();
    }
}

class PostsRepository implements PostsRepositoryInterface {
    public function get(string $uuid): Article { $query = "SELECT * FROM articles WHERE article_uuid = :uuid";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':uuid', $uuid);
        $statement->execute();
        $data = $statement->fetch();

        return new Article($data['article_uuid'], $data['author_uuid'], $data['title'], $data['content']);
    }

    public function save(Article $article): void {
        $query = "INSERT INTO articles (article_uuid, author_uuid, title, content) VALUES (:article_uuid, :author_uuid, :title, :content)";
        $statement = $this->db->prepare($query);
        $statement->bindValue(':article_uuid', $article->getUuid());
        $statement->bindValue(':author_uuid', $article->getAuthorUuid());
        $statement->bindValue(':title', $article->getTitle());
        $statement->bindValue(':content', $article->getContent());
        $statement->execute();
    }
}
?>