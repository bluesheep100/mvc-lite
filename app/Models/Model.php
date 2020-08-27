<?php

namespace App\Models;

use App\DB;
use JsonSerializable;

abstract class Model implements JsonSerializable
{
    protected $attributes = [];

    protected static $table = null;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        return $this->attributes[$name] = $value;
    }

    public function __toString(): string
    {
        return json_encode($this->attributes);
    }

    public function jsonSerialize()
    {
        return $this->attributes;
    }

    /**
     * Returns all instances of a Model from its associated table.
     * @return array
     */
    public static function all()
    {
        $items = DB::select(sprintf('select * from %s', static::$table));

        return array_map(function ($attributes) {
            return new static($attributes);
        }, $items);
    }

    /**
     * Searches for a Model with a given id,
     * and returns an object if found, otherwise null.
     *
     * @param $id
     * @return static|null
     */
    public static function find($id)
    {
        $res = DB::select(
            sprintf('select * from %s where id = ? limit 1', static::$table),
            [$id]
        );
        $res = array_pop($res);

        return $res !== null ? new static($res) : null;
    }

    public static function count()
    {
        $res = DB::select(sprintf('select COUNT(*) as count from %s', static::$table));

        return $res[0]['count'];
    }

    public function delete()
    {
        return DB::delete(
            sprintf('delete from %s where id = ? limit 1', static::$table),
            [$this->id]
        );
    }

    /**
     * Creates a new Model in the database.
     * @param $attributes
     * @return bool
     */
    abstract public static function create($attributes);

    /**
     * Updates the Model in the database.
     * @param $attributes
     * @return bool
     */
    abstract public function update($attributes);
}