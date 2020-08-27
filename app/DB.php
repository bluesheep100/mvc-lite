<?php

namespace App;

class DB
{
    protected static $pdo = null;

    public static function init()
    {
        $host = $_ENV['DB_HOST'];
        $database = $_ENV['DB_NAME'];
        self::$pdo = new \PDO('mysql:host='.$host.';dbname='.$database.';charset=UTF8', $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    /**
     * Executes the given SELECT query and returns the result of PDOStatement::fetchAll()
     *
     * @param $query
     * @param array $bindings
     * @return array
     */
    public static function select($query, $bindings = [])
    {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute($bindings);

        return $stmt->fetchAll();
    }

    /**
     * Executes the given query not of the SELECT type and returns a boolean indicating if it succeeded.
     *
     * @param $query
     * @param array $bindings
     * @return bool
     */
    protected static function nonSelect($query, $bindings = [])
    {
        $stmt = self::$pdo->prepare($query);

        return $stmt->execute($bindings);
    }

    /**
     * Executes the given INSERT query and returns a boolean indicating if it succeeded.
     *
     * @param $query
     * @param array $bindings
     * @return bool
     */
    public static function insert($query, $bindings = [])
    {
        return self::nonSelect($query, $bindings);
    }

    /**
     * Executes the given UPDATE query and returns a boolean indicating if it succeeded.
     *
     * @param $query
     * @param array $bindings
     * @return bool
     */
    public static function update($query, $bindings = [])
    {
        return self::nonSelect($query, $bindings);
    }

    /**
     * Executes the given DELETE query and returns a boolean indicating if it succeeded.
     *
     * @param $query
     * @param array $bindings
     * @return bool
     */
    public static function delete($query, $bindings = [])
    {
        return self::nonSelect($query, $bindings);
    }
}

DB::init();
