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

namespace MobileTest\Controller;

use Admin\Repository\UserRepository;
use Exception;
use MobileTest\AbstractMock;
use Mobile\Controller\RestController;
use Zend\Db\ResultSet\ResultSet;

/**
 * Class RestControllerTest
 * @package MobileTest\Controller
 */
class RestControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testCustomersActionSuccess()
    {
        $mock = $this->getMockBuilder(ResultSet::class)
            ->onlyMethods(['toArray'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects(self::exactly(0))
            ->method('toArray')
            ->willReturn([1 => 123]);
        $this->serviceManager->setService(ResultSet::class, $mock);

        $this->dispatch('/mobile/r1/customers', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Mobile');
        $this->assertControllerName(RestController::class);
        $this->assertControllerClass('RestController');
        $this->assertMatchedRouteName('mobile/r1');
        $response = $this->getResponse()->getContent();
        self::assertJson($response, 'Response is not Json');
        self::assertJsonStringEqualsJsonString(
            file_get_contents(
                __DIR__ . '/../../test/data/Controller/Rest/GetCustomersSuccess.json'
            ),
            $response
        );
    }

    /**
     * @throws Exception
     */
    public function testCustomerActionSuccess()
    {
        $this->dispatch('/mobile/r1/customer/1', 'GET', []);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Mobile');
        $this->assertControllerName(RestController::class);
        $this->assertControllerClass('RestController');
        $this->assertMatchedRouteName('mobile/r1');
        $response = $this->getResponse()->getContent();
        self::assertJson($response, 'Response is not Json');
        self::assertJsonStringEqualsJsonString(
            file_get_contents(
                __DIR__ . '/../../test/data/Controller/Rest/GetCustomerSuccess.json'
            ),
            $response
        );
    }

    /**
     * @throws Exception
     */
    public function testSearchActionSuccess()
    {
        $mock = $this->getMockBuilder(UserRepository::class)
            ->onlyMethods(['getUserByName'])
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects(self::exactly(0))
            ->method('getUserByName')
            ->willReturn([1 => 123]);
        $this->serviceManager->setService(UserRepository::class, $mock);

//        $stub = $this->createMock(UserRepository::class);
//        $stub->method('getUserByName')->willReturn([1=>123]);
//        $st = $stub->getUserByName('lalafa222');

        $this->dispatch('/mobile/r1/search', 'POST', ['searchinput' => 'admin6']);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Mobile');
        $this->assertControllerName(RestController::class);
        $this->assertControllerClass('RestController');
        $this->assertMatchedRouteName('mobile/r1');
        $response = $this->getResponse()->getContent();
        self::assertJson($response, 'Response is not Json');
        self::assertJsonStringEqualsJsonString(
            file_get_contents(
                __DIR__ . '/../../test/data/Controller/Rest/PostSearchSuccess.json'
            ),
            $response
        );
    }

    /**
     * @throws Exception
     */
    public function testIndexActionViewModelTemplateRenderedWithinLayout()
    {
        $this->dispatch('/', 'GET');
        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
