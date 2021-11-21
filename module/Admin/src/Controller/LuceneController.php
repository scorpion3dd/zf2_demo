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

namespace Admin\Controller;

use Admin\Form\GenereteForm;
use Admin\Form\SearchForm;
use Admin\Service\LuceneService;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;
use Exception;

/**
 * Class LuceneController
 * @package Admin\Controller
 */
class LuceneController extends ActionController
{
    /** @var SearchForm<array> $searchForm */
    private SearchForm $searchForm;
    /** @var GenereteForm<array> $genereteForm */
    private GenereteForm $genereteForm;
    /** @var LuceneService $luceneService */
    private LuceneService $luceneService;

    /**
     * LuceneController constructor.
     *
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param SearchForm<array> $searchForm
     * @param GenereteForm<array> $genereteForm
     * @param LuceneService $luceneService
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        SearchForm $searchForm,
        GenereteForm $genereteForm,
        LuceneService $luceneService
    ) {
        parent::__construct($authService, $logger);
        $this->genereteForm = $genereteForm;
        $this->searchForm = $searchForm;
        $this->luceneService = $luceneService;
        $this->getLuceneService()->registerZendSearch();
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }

    /**
     * @return Response|ViewModel<array>
     * @throws Exception
     */
    public function genereteAction()
    {
        $count = '10';
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $count = (int)$request->getPost()->get('count');
            if (! empty($count) && $count > 0) {
                try {
                    set_time_limit(0);
                    $start = microtime(true);
                    $this->getLuceneService()->genereteSaveUsers($count);
                    $end = microtime(true);
                    $time = round($end - $start, 2);

                    return $this->redirect()->toRoute(
                        'admin/lucene',
                        ['action' => 'confirm-generete'],
                        ['query' => [
                            'time' => $time,
                            'count' => $count,
                        ]]
                    );
                } catch (Exception $fault) {
                    $this->getLogger()->err($fault->getMessage());
                    $error = 'ERROR - Faker\Generator: '
                        . 'faultcode = ' . $fault->getCode()
                        . ', faultstring = ' . $fault->getMessage();
                    throw new Exception($error);
                }
            }
        }
        $form = $this->getGenereteForm();
        $form->get('count')->setValue($count);

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return ViewModel<array>
     */
    public function confirmGenereteAction(): ViewModel
    {
        return new ViewModel([
            'time' => $this->params()->fromQuery('time'),
            'count' => $this->params()->fromQuery('count'),
        ]);
    }

    /**
     * @return ViewModel<array>
     */
    public function indexingAction(): ViewModel
    {
        $query = '';
        $searchResults = [];
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $query = $request->getPost()->get('query');
            try {
                $searchResults = $this->getLuceneService()->findLucene($query);
            } catch (Exception $fault) {
                $this->getLogger()->err($fault->getMessage());
            }
        }
        $form  = $this->getSearchForm();
        $form->get('query')->setValue($query);

        return new ViewModel([
            'form' => $form,
            'searchResults' => $searchResults,
            'status' => $this->params()->fromQuery('status'),
            'count' => $this->params()->fromQuery('count'),
        ]);
    }

    /**
     * ZendSearch Lucene
     * https://github.com/zendframework/ZendSearch
     * Install ZendSearch:
     * - cd vendor
     * - git clone https://github.com/zendframework/ZendSearch.git ZendSearch
     *
     * @return Response
     * @throws Exception
     */
    public function generateIndexAction(): Response
    {
        try {
            $count = $this->getLuceneService()->generateLuceneIndex();
        } catch (Exception $fault) {
            $count = 0;
            $this->getLogger()->err($fault->getMessage());
        }

        return $this->redirect()->toRoute(
            'admin/lucene',
            ['action' => 'indexing'],
            ['query' => [
                'status' => 'OK',
                'count' => $count
            ]]
        );
    }

    /**
     * @return SearchForm<array>
     */
    private function getSearchForm(): SearchForm
    {
        return $this->searchForm;
    }

    /**
     * @return GenereteForm<array>
     */
    private function getGenereteForm(): GenereteForm
    {
        return $this->genereteForm;
    }

    /**
     * @return LuceneService
     */
    private function getLuceneService(): LuceneService
    {
        return $this->luceneService;
    }
}
