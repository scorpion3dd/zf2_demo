<?php
/**
 * This file is part of the Simple demo web-project with REST Full API for Mobile.
 *
 * This project is no longer maintained.
 * The project is written in Zend Framework 2 Release.
 *
 * @link https://github.com/scorpion3dd
 * @copyright Copyright (c) 2016-2021 Denis Puzik <scorpion3dd@gmail.com>
 */

namespace Admin\Entity;

use Zend\Stdlib\ParametersInterface;

/**
 * Class User
 * @package Admin\Entity
 */
class User extends AbstractEntity
{
    public int $id;
    public string $name;
    public string $email;
    public string $birthday;
    public string $phone;
    public string $address;
    public string $description;
    public int $type;
    public string $password;
    public string $confirmPassword;
    public string $created;
    public string $updated;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->exchangeArray([]);
    }

    /**
     * @param ParametersInterface<array>|array<array> $data
     */
    public function exchangeArray($data): void
    {
        $this->id = (! empty($data['id'])) ? $data['id'] : 0;
        $this->name = (! empty($data['name'])) ? $data['name'] : '';
        $this->email = (! empty($data['email'])) ? $data['email'] : '';
        $this->birthday = (! empty($data['birthday'])) ? $data['birthday'] : '';
        $this->phone = (! empty($data['phone'])) ? $data['phone'] : '';
        $this->address = (! empty($data['address'])) ? $data['address'] : '';
        $this->description = (! empty($data['description'])) ? $data['description'] : '';
        $this->type = (! empty($data['type'])) ? $data['type'] : 1;
        $this->password = (! empty($data['password'])) ? $data['password'] : '';
        $this->confirmPassword = (! empty($data['confirmPassword'])) ? $data['confirmPassword'] : '';
        $this->created = (! empty($data['created'])) ? $data['created'] : '';
        $this->updated = (! empty($data['updated'])) ? $data['updated'] : '';
    }

    /**
     * @param string $confirmPassword
     */
    public function setConfirmPassword(string $confirmPassword): void
    {
        $this->confirmPassword = md5($confirmPassword);
    }

    /**
     * @return bool
     */
    public function checkPasswords(): bool
    {
        return $this->confirmPassword == $this->password ? true : false;
    }

    /**
     * @param string $clear_password
     */
    public function setPassword(string $clear_password):void
    {
        $this->password = md5($clear_password);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
