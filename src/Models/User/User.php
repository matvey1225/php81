<?php

namespace Matvey\Test\Models\User;

include_once __DIR__ . '/../../../vendor/autoload.php';

use http\Params;
use Matvey\Test\Db\Db;
use Matvey\Test\Model\Model;

class User extends Model
{
    protected static string $table = 'users';
    protected string $token = '';
    protected string $password = '';
    protected string $login = '';
    protected string $name = '';
    protected int $id = 0;
    protected string $role = 'user';

    public function getUser(): array
    {
        return ['name' => $this->name, 'login' => $this->login,
            'password' => $this->password, 'token' => $this->token, 'id' => $this->id,];
    }


    public function getPassword(): string
    {
        return $this->password;
    }

    public function getToken(): string
    {
        return $this->token;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param array $data - $request->getParseBody()
     * @param string $mode
     * @return User|null
     */

    public function parseUserFromBodyHttp(array $data, string $mode): User|null
    {
        if ($this->completelyFilledBody($data, $mode)
        ) {
            if (isset($data['name'])) {
                $this->name = $data['name'];
            }
            $this->login = $data['login'];
            $this->password = md5(trim($data['password']));
            $this->token = md5(trim($data['login']) . trim($data['password']));
            return $this;
        } else {
            return null;
        }

    }

    public function completelyFilledBody(array $data, string $mode): ?bool
    {
        if ($mode === 'registration') {
            return ((isset($data['name'])) && (isset($data['password'])) && (isset($data['login']))
                && (!empty($data['name'])) && (!empty($data['password'])) && (!empty($data['login'])));
        }
        if ($mode === 'login') {
            return ((isset($data['password'])) && (isset($data['login']))
                && (!empty($data['password'])) && (!empty($data['login'])));
        }
        return null;
    }


//not use
    public function setUser(string $name, string $login, string $password): static
    {
        $this->name = $name;
        $this->login = $login;
        $this->password = md5(trim($password));
        $this->token = md5($login . $password);
        return $this;
    }


    /**
     * @param string $login
     * @return User возвращает логин юзера
     *
     * возвращает логин юзера
     */
    public static function getUserByParams(string $params, string $value): User
    {
        $user = Db::query('SELECT * FROM users WHERE ' . $params . ' = ?', self::class, [$value]);
        return $user[0];
    }

    public function identification(): User|false
    {
        $userFromDb = null;
        if ($this->existUser()) {
            $userFromDb = User::getUserByParams('login', $this->getLogin());
            if ($userFromDb->getPassword() === $this->getPassword()) {
                return $userFromDb;
            }
        }
        return false;
    }

    /**
     * @param string $login
     *
     * @return bool
     * проверка на существование пользователя по логину
     */

    public function existUser(): bool
    {
        return (bool)Db::query('SELECT * FROM users WHERE login = ?', static::class, [$this->login]);
    }

}