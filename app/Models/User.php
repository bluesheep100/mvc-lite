<?php

namespace App\Models;

use App\DB;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property string password
 */
class User extends Model
{
    protected static $table = 'users';

    public static function create($attributes)
    {
        return DB::insert('insert into users (name, email, password) values(?,?,?)', [
            $attributes['name'],
            $attributes['email'],
            password_hash($attributes['password'], PASSWORD_BCRYPT),
        ]);
    }

    /** @inheritDoc */
    public function update($attributes)
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = password_hash($attributes['password'], PASSWORD_BCRYPT);
        }

        return DB::update('update users set name = ?, email = ?, password = ? where id = ?', [
            $attributes['name'] ?? $this->name,
            $attributes['email'] ?? $this->email,
            $attributes['password'] ?? $this->password,
            $this->id,
        ]);
    }
}
