<?php

namespace src\Http\Actions\Like;

use myHttp\Request;
use myHttp\Response;
use src\Repositories\LikeRepositoryInterface;
use src\Http\SuccessfullResponse;

class AddLike
{
    private LikeRepositoryInterface $likeRepository;

    public function __construct(LikeRepositoryInterface $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    public function handle(Request $request): Response
    {
        $postUuid = $request->get('post_uuid');
        $userUuid = $request->get('user_uuid');

        // Проверка наличия корректных UUID для статьи и пользователя
        if (!UUID::isValid($postUuid) || !UUID::isValid($userUuid)) {
            // Обработка ошибки, например, возвращение ErrorResponse
            return new ErrorResponse([
                'success' => false,
                'message' => 'Некорректные UUID для статьи или пользователя.',
            ]);
        }

        try {
            $like = new Like(UUID::random(), $postUuid, $userUuid);
            $this->likeRepository->save($like);

            return new SuccessfullResponse([
                'success' => true,
                'message' => 'Лайк успешно добавлен.',
            ]);
        } catch (\RuntimeException $e) {
            return new ErrorResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}