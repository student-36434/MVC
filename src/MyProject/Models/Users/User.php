<?php

namespace MyProject\Models\Users;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;

class User extends ActiveRecordEntity
{
    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;

    /** @var string */
    protected $createdAt;

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
    }

    public static function signUp(array $userData): User // регистрация пользователя
    {
        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Enter your nickname');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])) {
            throw new InvalidArgumentException('Nickname can only consist of symbols of the Latin alphabet and numbers');
        }

        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Enter your email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email is incorrect');
        }

        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Enter your password');
        }

        if (mb_strlen($userData['password']) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters');
        }

        if(static::findOneByColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('User with this nickname already exists');
        }

        if(static::findOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('User with this email already exists');
        }

        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->isConfirmed = false;
        $user->role = 'user';
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();

        return $user;
    }

    public static function login(array $loginData): User
    {
        if (empty($loginData['email'])) {
            throw new InvalidArgumentException('Enter your email');
        }

        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Enter your password');
        }

        $user = User::findOneByColumn('email', $loginData['email']);
        if ($user === null) {
            throw new InvalidArgumentException('No user with this email');
        }

        if (!password_verify($loginData['password'], $user->getPasswordHash())) {   // Проверяет, соответствует ли пароль хешу
            throw new InvalidArgumentException('Invalid password');
        }

        if (!$user->isConfirmed) {
            throw new InvalidArgumentException('User not verified');
        }

        $user->refreshAuthToken(); // создание токена
        $user->save();

        return $user;
    }

    protected static function getTableName(): string
    {
        return 'users';
    }

    // Автотокен по которому проверяется залогинен ли пользователь
    private function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
}
