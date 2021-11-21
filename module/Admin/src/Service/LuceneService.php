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

namespace Admin\Service;

use Admin\Entity\Upload;
use Admin\Entity\User;
use Admin\Repository\UploadRepository;
use Admin\Repository\UserRepository;
use Exception;
use Faker\Factory;
use Zend\Loader\StandardAutoloader;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\SearchIndexInterface;

/**
 * Class LuceneService
 * @package Admin\Service
 */
class LuceneService extends AbstractService
{
    private UserRepository $userTable;
    private UploadRepository $uploadTable;

    /**
     * LuceneService constructor.
     * @param array<array> $config
     * @param UserRepository $userTable
     * @param UploadRepository $uploadTable
     */
    public function __construct(array $config, UserRepository $userTable, UploadRepository $uploadTable)
    {
        parent::__construct($config);
        $this->userTable = $userTable;
        $this->uploadTable = $uploadTable;
    }

    /**
     * @return UserRepository
     */
    public function getUserTable(): UserRepository
    {
        return $this->userTable;
    }

    /**
     * @return UploadRepository
     */
    public function getUploadTable(): UploadRepository
    {
        return $this->uploadTable;
    }

    /**
     * registerZendSearch
     */
    public function registerZendSearch(): void
    {
        $loader = new StandardAutoloader(['autoregister_zf' => true]);
        $loader->registerNamespace(
            'ZendSearch',
            __DIR__ . "/../../../../vendor/ZendSearch/library/ZendSearch"
        );
        $loader->register();
    }

    /**
     * @param int $count
     *
     * @return array<int, User>
     * @throws Exception
     */
    public function genereteSaveUsers(int $count): array
    {
        $items = [];
        $faker = Factory::create();
        for ($i = 0; $i < $count; $i++) {
            $item = new User();
            $item->birthday = $faker->date('Y-m-d');
            $item->name = $faker->name;
            $item->email = $faker->email;
            $password = $faker->password();
            $item->setPassword($password);
            $item->setConfirmPassword($password);
            $item->phone = $faker->phoneNumber;
            $item->address = $faker->address;
            $item->description = $faker->text;
            $item->type = $faker->numberBetween(1, 5);

            $item->id = $this->getUserTable()->saveUser($item);
            $items[] = $item;
        }

        return $items;
    }

    /**
     * @return object|null
     */
    public function createLucene(): ?object
    {
        if (class_exists(Lucene::class)) {
            return Lucene::create($this->getLocationPath('search_index'));
        } else {
            return null;
        }
    }

    /**
     * @param string $query
     *
     * @return array<array>
     */
    public function findLucene(string $query): array
    {
        /** @phpstan-ignore-next-line */
        return Lucene::open($this->getLocationPath('search_index'))->find($query);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function generateLuceneIndex(): int
    {
        $count = 0;
        if (class_exists(Lucene::class)) {
            $index = $this->createLucene();
            $userTable = $this->getUserTable();
            $uploadTable = $this->getUploadTable();
            $allUploads = $uploadTable->fetchAll();
            /** @var Upload $fileUpload */
            foreach ($allUploads as $fileUpload) {
                $uploadOwner = $userTable->getUser($fileUpload->user_id);
                /** @phpstan-ignore-next-line */
                $fileUploadId = Document\Field::unIndexed('upload_id', $fileUpload->id);
                /** @phpstan-ignore-next-line */
                $label = Document\Field::Text('label', $fileUpload->label);
                /** @phpstan-ignore-next-line */
                $owner = Document\Field::Text('owner', $uploadOwner->name);

                $uploadPath = $this->getLocationPath('upload_location');
                if (substr_compare($fileUpload->filename, ".xlsx", strlen($fileUpload->filename) - strlen(".xlsx"), strlen(".xlsx")) === 0) {
                    /** @phpstan-ignore-next-line */
                    $indexDoc = Document\Xlsx::loadXlsxFile($uploadPath ."/" . $fileUpload->filename);
                } elseif (substr_compare($fileUpload->filename, ".docx", strlen($fileUpload->filename) - strlen(".docx"), strlen(".docx")) === 0) {
                    /** @phpstan-ignore-next-line */
                    $indexDoc = Document\Docx::loadDocxFile($uploadPath ."/" . $fileUpload->filename);
                } else {
                    /** @phpstan-ignore-next-line */
                    $indexDoc = new Document();
                }
                /** @phpstan-ignore-next-line */
                $class = Document\Field::Text('class', 'Upload');
                $indexDoc->addField($class);
                $indexDoc->addField($label);
                $indexDoc->addField($owner);
                $indexDoc->addField($fileUploadId);
                /** @phpstan-ignore-next-line */
                $index->addDocument($indexDoc);
                $count++;
            }

            $allUsers = $userTable->fetchAll();
            /** @var User $user */
            foreach ($allUsers as $user) {
                /** @phpstan-ignore-next-line */
                $indexDoc = new Document();
                /** @phpstan-ignore-next-line */
                $userId = Document\Field::unIndexed('user_id', $user->id);
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($userId);
                /** @phpstan-ignore-next-line */
                $name = Document\Field::Text('name', $user->name);
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($name);
                /** @phpstan-ignore-next-line */
                $description = Document\Field::Text('description', $user->description);
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($description);
                /** @phpstan-ignore-next-line */
                $birthday = Document\Field::Text('birthday', $user->birthday);
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($birthday);
                /** @phpstan-ignore-next-line */
                $email = Document\Field::Text('email', $user->email);
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($email);
                /** @phpstan-ignore-next-line */
                $phone = Document\Field::Text('phone', $user->phone);
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($phone);
                /** @phpstan-ignore-next-line */
                $address = Document\Field::Text('address', $user->address);
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($address);
                /** @phpstan-ignore-next-line */
                $class = Document\Field::Text('class', 'User');
                /** @phpstan-ignore-next-line */
                $indexDoc->addField($class);
                /** @phpstan-ignore-next-line */
                $index->addDocument($indexDoc);
                $count++;
            }
            /** @phpstan-ignore-next-line */
            $index->commit();
        }

        return $count;
    }
}
