<?php

namespace MyProject\Models\Articles;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;

class Article extends ActiveRecordEntity
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $text;

    /** @var int */
    protected $authorId;

    /** @var string */
    protected $createdAt;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param User $user
     */
    public function setAuthor(User $user): void
    {
        $this->authorId = $user->getId();
    }

    //создание новой статьи
    public static function createFromArray(array $fields, User $author): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Article title not submitted');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('The text of the article has not been submitted');
        }

        $article = new Article();

        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);

        $article->save();

        return $article;
    }

    //Обновление статьи
    public function updateFromArray(array $fields): Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Article title not submitted');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('The text of the article has not been submitted');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);

        $this->save();

        return $this;
    }

    protected static function getTableName(): string
    {
        return 'articles';
    }
}