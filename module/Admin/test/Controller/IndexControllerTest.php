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

use Admin\Controller\IndexController;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class IndexControllerTest
 * @package AdminTest\Controller
 */
class IndexControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testIndexActionSuccess()
    {
        $this->dispatch('/admin', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('admin');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('admin');
        $response = $this->getResponse()->getContent();
        self::assertTrue($this->HTMLValidate($response));
        $expected = file_get_contents(
            __DIR__ . '/../../test/data/Controller/Index/GetIndexActionSuccess.html'
        );
        self::assertSame($this->trim($expected), $this->trim($response));
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
