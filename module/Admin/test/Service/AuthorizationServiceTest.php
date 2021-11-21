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

use Admin\Service\AuthorizationService;
use Exception;
use MobileTest\AbstractMock;

/**
 * Class AuthorizationServiceTest
 * @package AdminTest\Service
 */
class AuthorizationServiceTest extends AbstractMock
{
    private AuthorizationService $authorizationService;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorizationService = $this->serviceManager->get('AuthorizationService');
    }

    /**
     * @throws Exception
     */
    public function testCheckAuthorization()
    {
        $res = $this->authorizationService->checkAuthorization();
        $this->assertEquals(true, $res);
    }
}
