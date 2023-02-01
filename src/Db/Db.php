<?php

namespace Matvey\Test\Db;

include_once __DIR__ . '/../../vendor/autoload.php';

use PDO;

class Db
{
    /**
     * @return PDO
     */
    protected static function includeDb(): PDO
    {
        return new   PDO('mysql:host=mysql_db;dbname=homework;charset=utf8mb4', 'root', 'root');
    }

    /**
     * Insert,Update
     * @param string $sql
     * @param array|null $data
     * @return bool
     */
    public static function execute(string $sql, array $data = null): bool
    {
        $dbh = Db:: includeDb();
        $sth = $dbh->prepare($sql);
        return (bool)$sth->execute($data);
    }

    /**
     * SELECT
     * @param string $sql
     * @param string|null $class
     * @param array $data
     * @return array|false
     */

    public static function query(string $sql, string $class = null, array $data = []): array|null
    {
//var_dump($sql);
        $dbh = Db:: includeDb();
        $sth = $dbh->prepare($sql);
        $sth->execute($data);
        $data = null;
        if ($class != null) {
            $data= $sth->fetchAll(\PDO::FETCH_CLASS, $class);
        } else {
            $data= $sth->fetchAll();
        }
        return ($data!==false)? $data : null;

    }

}