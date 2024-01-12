<?php

namespace src\Repositories;

use src\Model\User;
use src\Model\UUID;

interface UserRepositoryInterface {
    /**
     * Сохраняет пользователя в репозитории.
     *
     * @param User $user Пользователь для сохранения.
     * @throws \src\Exceptions\UserIncorrectDataException Если данные пользователя некорректны.
     */
    public function save(User $user): void;

    /**
     * Получает пользователя по UUID.
     *
     * @param UUID $uuid UUID пользователя.
     * @return User Найденный пользователь.
     * @throws \src\Exceptions\UserIncorrectDataException Если данные пользователя некорректны.
     * @throws \src\Exceptions\UserNotFoundException Если пользователь не найден.
     */
    public function get(UUID $uuid): User;

    /**
     * Получает пользователя по имени пользователя (username).
     *
     * @param string $username Имя пользователя.
     * @return User Найденный пользователь.
     * @throws \src\Exceptions\UserNotFoundException Если пользователь не найден.
     */
    public function getByUsername(string $username): User;
}
