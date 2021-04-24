<?php

namespace MyProject\Services;

use MyProject\Exceptions\DbException;

class Db
{
    private static $instance; // Хранится текущий обьект

    /** @var \PDO */
    private $pdo;

    private function __construct() // конструктор приватный, чтобы нельзя было поза классом создать обьект
    {
        $dbOptions = (require __DIR__ . '/../../settings.php')['db'];

        try {
            $this->pdo = new \PDO(
                'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            $this->pdo->exec('SET NAMES UTF8');
        } catch (\PDOException $e) {
            throw new DbException('Error while connecting to database:');
        }
    }

    public function query(string $sql, array $params = [], string $className = 'stdClass'): ?array // stdClass - встроенный класс в PHP, без свойств и методов
    {
        $sth = $this->pdo->prepare($sql); // Подготавливает запрос к выполнению и возвращает связанный с этим запросом объект
        $result = $sth->execute($params); // Запускает подготовленный запрос на выполнение

        if (false === $result) {
            return null;
        }

        // Возвращает массив, содержащий все строки результирующего набора
        return $sth->fetchAll(\PDO::FETCH_CLASS, $className); //PDO::FETCH_CLASS: Будет создан и возвращён новый объект указанного класса
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {     // Проверяет существует ли обьект,
            self::$instance = new self();   // Если нет, то создает обьект(подключение к базе)
        }

        return self::$instance;            // Если да, то возращает текущий.
    }

    public function getLastInsertId(): int // Возвращает ID последней вставленной строки
    {
        return (int) $this->pdo->lastInsertId();
    }
}
