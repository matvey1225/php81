<?php

namespace Matvey\Test\Models\User;

include_once __DIR__ . '/../../../vendor/autoload.php';


use Matvey\Test\Db\Db;
use Matvey\Test\Model\Model;
use Matvey\Test\Models\Role\Role;

class User extends Model
{
    public const LOGIN = 'login';
    public const PASSWORD= 'password';
    public const REGISTRATION = 'registration';

    protected static string $table = 'users';
    protected string $token = '';
    protected string $password = '';
    protected string $login = '';
    protected string $name = '';
    protected int $id = 0;
    protected string $role = Role::USER;

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
     * @param array $data -
     * @param string $mode User::LOGIN || User::REGISTRATION
     * @return User|null
     */
    public function parseUserFromBodyHttp(array $data, string $mode): User|null
    {
        if ($this :: correctFilledBody($data, $mode)
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

    /**
     * @param array $data
     * @param string $mode
     * @return bool|null
     */
    public static function correctFilledBody(array $data, string $mode): ?bool
    {
        if ($mode === self::REGISTRATION) {
            return ((isset($data['name'])) && (isset($data['password'])) && (isset($data['login']))
                && (!empty($data['name'])) && (!empty($data['password'])) && (!empty($data['login'])));
        }
        if ($mode === self::LOGIN) {
            return ((isset($data['password'])) && (isset($data['login']))
                && (!empty($data['password'])) && (!empty($data['login'])));
        }
        return null;
    }


    /**
     * @param string $params
     * @param string $value
     * @return User|null
     */
    public static function getUserByParams(string $params, string $value): ?User
    {
        $user = Db::query('SELECT * FROM users WHERE ' . $params . ' = ?', self::class, [$value]);
        if (empty($user)) {
            return null;
        }
        return $user[0];
    }

    public function identification(): User|false
    {
        if ($this->existUser()) {
            $userFromDb = User::getUserByParams(self::LOGIN, $this->getLogin());
            if ($userFromDb->getPassword() === $this->getPassword()) {
                return $userFromDb;
            }
        }
        return false;
    }

    public function existUser(): bool
    {
        return (bool)Db::query('SELECT * FROM users WHERE login = ?', self::class, [$this->login]);
    }

}