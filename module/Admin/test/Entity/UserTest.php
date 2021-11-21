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

namespace AdminTest\Entity;

use Admin\Entity\User;
use MobileTest\AbstractMock;

/**
 * Class UserTest
 * @package AdminTest\Entity
 */
class UserTest extends AbstractMock
{
    public function testGetPassword()
    {
        $clear_password = '1234567890';
        $user = new User();
        $user->setPassword($clear_password);
        $this->assertSame(md5($clear_password), $user->getPassword());
    }

    public function testCheckPasswords()
    {
        $clear_password = '1234567890';
        $user = new User();
        $user->setPassword($clear_password);
        $user->setConfirmPassword($clear_password);
        $this->assertSame(true, $user->checkPasswords());

        $user->setConfirmPassword($clear_password . '000');
        $this->assertSame(false, $user->checkPasswords());

        $stub = $this->createMock(User::class);
        $stub->method('checkPasswords')->willReturn(true);
        $this->assertSame(true, $stub->checkPasswords());

        $stub2 = $this->createMock(User::class);
        $stub2->method('checkPasswords')->willReturn(false);
        $this->assertSame(false, $stub2->checkPasswords());
    }

    public function testExchangeArray()
    {
        $id = 30;
        $password = 'd8578edf8458ce06fbc5bb76a58c5ca4';
        $confirmPassword = 'd8578edf8458ce06fbc5bb76a58c5ca4';
        $name = 'Ivan';
        $email = 'ivan@gmail.com';
        $birthday = '1985-07-30';
        $phone = '0987779988';
        $address = 'London';
        $user = new User();
        $user->exchangeArray([
            'id' => $id,
            'password' => $password,
            'confirmPassword' => $confirmPassword,
            'name' => $name,
            'email' => $email,
            'birthday' => $birthday,
            'phone' => $phone,
            'address' => $address,
        ]);
        $this->assertSame($id, $user->id);
        $this->assertSame($password, $user->password);
        $this->assertSame($confirmPassword, $user->confirmPassword);
        $this->assertSame($name, $user->name);
        $this->assertSame($email, $user->email);
        $this->assertSame($birthday, $user->birthday);
        $this->assertSame($phone, $user->phone);
        $this->assertSame($address, $user->address);
    }
}
