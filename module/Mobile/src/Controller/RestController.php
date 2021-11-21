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

namespace Mobile\Controller;

use Admin\Controller\ActionController;
use Admin\Repository\UserRepository;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Http\Headers;
use Zend\Log\Logger;
use Zend\Stdlib\ResponseInterface;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class RestController
 * @package Mobile\Controller
 */
class RestController extends ActionController
{
    private UserRepository $userTable;

    /**
     * RestController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param UserRepository $userTable
     */
    public function __construct(Logger $logger, AuthenticationService $authService, UserRepository $userTable)
    {
        parent::__construct($authService, $logger);
        $this->userTable = $userTable;
    }

    /**
     * @return Response|ResponseInterface
     * @throws Exception
     */
    public function customerAction()
    {
        return $this->getJson($this->getUserTable()->getUser($this->params()->fromRoute('id')));
    }

    /**
     * @return Response|ResponseInterface
     */
    public function customersAction()
    {
        /** @var ResultSet $resultSet */
        $resultSet = $this->getUserTable()->fetchAll();
        $result = $resultSet->toArray();

        return $this->getJson($result);
    }

    /**
     * @return Response|ResponseInterface
     */
    public function searchAction()
    {
        try {
            $userName = $this->params()->fromPost('searchinput');
            $users = $this->getUserTable()->getUserByName($userName);
        } catch (Exception $exception) {
            $users = [];
        }

        return $this->getJson($users);
    }

    /**
     * @param mixed $results
     *
     * @return Response
     */
    private function getJson($results): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var Headers $headers */
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/json');
        $response->setContent(json_encode($results));

        return $response;
    }

    /**
     * @return UserRepository
     */
    private function getUserTable(): UserRepository
    {
        return $this->userTable;
    }
}
