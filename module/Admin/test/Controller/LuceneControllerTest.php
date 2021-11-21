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

namespace AdminTest\Controller;

use Admin\Controller\LuceneController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class LuceneControllerTest
 * @package AdminTest\Controller
 */
class LuceneControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testGetIndexActionSuccess()
    {
        $this->dispatch('/admin/lucene', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(LuceneController::class);
        $this->assertControllerClass('LuceneController');
        $this->assertMatchedRouteName('admin/lucene');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Lucene/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetGenereteActionSuccess()
    {
        $this->dispatch('/admin/lucene/generete', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(LuceneController::class);
        $this->assertControllerClass('LuceneController');
        $this->assertMatchedRouteName('admin/lucene');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Lucene/GetGenereteActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testPostGenereteActionSuccess()
    {
        $this->dispatch('/admin/lucene/generete', 'POST', ['count' => '2']);
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(LuceneController::class);
        $this->assertControllerClass('LuceneController');
        $this->assertMatchedRouteName('admin/lucene');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testGetConfirmGenereteActionSuccess()
    {
        $this->dispatch('/admin/lucene/confirm-generete', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(LuceneController::class);
        $this->assertControllerClass('LuceneController');
        $this->assertMatchedRouteName('admin/lucene');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Lucene/GetConfirmGenereteActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetIndexingActionSuccess()
    {
        $this->dispatch('/admin/lucene/indexing', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(LuceneController::class);
        $this->assertControllerClass('LuceneController');
        $this->assertMatchedRouteName('admin/lucene');
        $response = $this->getResponse()->getContent();
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Lucene/GetIndexingActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
    }

    /**
     * @throws Exception
     */
    public function testGetGenerateIndexActionSuccess()
    {
        $this->dispatch('/admin/lucene/generate-index', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertModuleName('admin');
        $this->assertControllerName(LuceneController::class);
        $this->assertControllerClass('LuceneController');
        $this->assertMatchedRouteName('admin/lucene');
        $response = $this->getResponse()->getContent();
        self::assertSame('', $response);
    }

    /**
     * @throws Exception
     */
    public function testInvalidRoute()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
