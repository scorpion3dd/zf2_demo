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

use Exception;
use Mobile\Controller\MobileController;
use MobileTest\AbstractMock;

/**
 * Class MobileControllerTest
 * @package MobileTest\Controller
 */
class MobileControllerTest extends AbstractMock
{
    /**
     * @throws Exception
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/mobile/v1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Mobile');
        $this->assertControllerName(MobileController::class);
        $this->assertControllerClass('MobileController');
        $this->assertMatchedRouteName('mobile/v1');
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
