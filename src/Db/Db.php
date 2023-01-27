<?php

namespace Matvey\Test\Db;

include_once __DIR__ . '/../../vendor/autoload.php';

use http\Exception\RuntimeException;
use PDO;
use PDOException;

class Db
{
    /**
     * @return PDO
     *
     * выполняет подключение к бд
     */
    protected static function includeDb(): PDO
    {
        return new   \PDO('mysql:host=mysql_db;dbname=homework;charset=utf8mb4', 'root', 'root');
    }

    /**
     * выполняет запрос SQL без возврата значений Insert,Update
     * @param string $sql запрос к бд
     * @param array|null $data
     * @return bool true - успешно/ иначе false
     */
    public static function execute(string $sql, array $data = null): bool
    {


        $dbh = Db:: includeDb();
        $sth = $dbh->prepare($sql);

        return (bool)$sth->execute($data);
    }

    /**
     * выполняет запрос SQL и возвращает значение
     *
     * @param string $sql - запрос
     * @param string|null $class
     * @param array $data
     * @return array|false - массив со значениями или false
     */
    public static function query(string $sql, string $class = null, array $data = []): array|false
    {

        $dbh = Db:: includeDb();
        $sth = $dbh->prepare($sql);
        $sth->execute($data);

        if ($class != null) {
            return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
        } else {
            return $sth->fetchAll();
        }

    }

}