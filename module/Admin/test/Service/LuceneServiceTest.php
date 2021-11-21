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

namespace AdminTest\Service;

use Admin\Service\LuceneService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class LuceneServiceTest
 * @package AdminTest\Service
 */
class LuceneServiceTest extends AbstractMock
{
    private LuceneService $luceneService;

    public function setUp(): void
    {
        parent::setUp();

        $this->luceneService = $this->serviceManager->get('LuceneService');
    }

    /**
     * @throws Exception
     */
    public function testGetUserTable()
    {
        $userRepository = $this->serviceManager->get('UserRepository');
        $res = $this->luceneService->getUserTable();
        $this->assertEquals($userRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testGetUploadTable()
    {
        $uploadRepository = $this->serviceManager->get('UploadRepository');
        $res = $this->luceneService->getUploadTable();
        $this->assertEquals($uploadRepository, $res);
    }

    /**
     * @throws Exception
     */
    public function testRegisterZendSearch()
    {
        $res = $this->luceneService->registerZendSearch();
        $this->assertEquals(null, $res);
    }
}
