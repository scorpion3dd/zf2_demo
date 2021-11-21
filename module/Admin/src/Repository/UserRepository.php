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

namespace Admin\Repository;

use Admin\Entity\User;
use DateTime;
use Exception;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

/**
 * Class UserRepository
 * @package Admin\Repository
 */
class UserRepository extends AbstractRepository
{
    /**
     * UserRepository constructor.
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @param User $user
     *
     * @return int
     * @throws Exception
     */
    public function saveUser(User $user): int
    {
        $data = [
            'email' => $user->email,
            'name'  => $user->name,
            'birthday'  => $user->birthday,
            'phone'  => $user->phone,
            'address'  => $user->address,
            'description'  => $user->description,
            'type'  => $user->type,
            'password'  => $user->password,
        ];
        $id = (int)$user->id;
        $date = new DateTime();
        $dateNow = $date->format('Y-m-d H:i:s');
        if ($id == 0) {
            $data['created'] = $dateNow;
            $data['birthday'] = $dateNow;
            if (isset($user->password)
                && isset($user->confirmPassword)
                && $user->confirmPassword == $user->password
            ) {
                $id = (int)$this->tableGateway->insert($data);
            } else {
                throw new Exception('User confirm password not equal password');
            }
        } else {
            $data['updated'] = $dateNow;
            if (empty($data['password'])) {
                unset($data['password']);
            }
            $this->tableGateway->update($data, ['id' => $id]);
        }

        return $id;
    }

    /**
     * @param int $id
     *
     * @return User|null
     */
    public function getUser(int $id): ?User
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        /** @var User $user */
        /** @phpstan-ignore-next-line */
        $user = $rowset->current();

        return $user;
    }

    /**
     * @param string $userName
     *
     * @return array<array>
     * @throws Exception
     */
    public function getUserByName(string $userName): array
    {
        /** @var ResultSet $rowset */
        $rowset = $this->tableGateway->select(['name' => $userName]);
        $rows = $rowset->toArray();
        if (! $rows) {
            throw new Exception("Could not find row $userName");
        }

        return $rows;
    }

    /**
     * @param string|null $userEmail
     * @throws Exception
     *
     * @return User|null
     */
    public function getUserByEmail(?string $userEmail = ''): ?User
    {
        /** @var ResultSet $rowset */
        $rowset = $this->tableGateway->select(['email' => $userEmail]);
        /** @var User $user */
        $user = $rowset->current();

        return $user;
    }

    /**
     * @param int $id
     */
    public function deleteUser(int $id): void
    {
        $this->tableGateway->delete(['id' => $id]);
    }
}
