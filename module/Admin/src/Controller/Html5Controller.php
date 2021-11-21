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

use Admin\Form\Html5Form;
use Admin\Form\MultiImageUploadForm;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;

/**
 * Class Html5Controller
 * @package Admin\Controller
 */
class Html5Controller extends ActionController
{
    /** @var MultiImageUploadForm<array> $multiImageUploadForm */
    private MultiImageUploadForm $multiImageUploadForm;
    /** @var Html5Form<array> $html5Form */
    private Html5Form $html5Form;

    /**
     * IndexController constructor.
     *
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param Html5Form<array> $html5Form
     * @param MultiImageUploadForm<array> $multiImageUploadForm
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        Html5Form $html5Form,
        MultiImageUploadForm $multiImageUploadForm
    ) {
        parent::__construct($authService, $logger);
        $this->html5Form = $html5Form;
        $this->multiImageUploadForm = $multiImageUploadForm;
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel<array>
     */
    public function formAction(): ViewModel
    {
        return new ViewModel(['form' => $this->getHtml5Form()]);
    }

    /**
     * @return Response|ViewModel<array>
     */
    public function multiUploadAction()
    {
        $form = $this->getMultiImageUploadForm();
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();

                return $this->redirect()->toRoute('admin/html5', [
                    'action' => 'processMultiUpload',
                    'data' => $data
                ]);
            }
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return ViewModel<array>
     */
    public function processMultiUploadAction(): ViewModel
    {
        return new ViewModel();
    }

    /**
     * @return MultiImageUploadForm<array>
     */
    private function getMultiImageUploadForm(): MultiImageUploadForm
    {
        return $this->multiImageUploadForm;
    }

    /**
     * @return Html5Form<array>
     */
    private function getHtml5Form(): Html5Form
    {
        return $this->html5Form;
    }
}
