<?php
namespace src\Repositories;

use src\Model\Like;
use src\Model\UUID;

interface LikeRepositoryInterface
{
    public function save(Like $like): void;

    public function getByPostUuid(UUID $postUuid): array;
}
