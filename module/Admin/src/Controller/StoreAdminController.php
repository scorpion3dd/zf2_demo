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

use Admin\Form\ProductForm;
use Admin\Service\StoreAdminService;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\View\Model\ViewModel;

/**
 * Class StoreAdminController
 * @package Admin\Controller
 */
class StoreAdminController extends ActionController
{
    /** @var StoreAdminService $storeAdminService */
    private StoreAdminService $storeAdminService;
    /** @var ProductForm<array> $productForm */
    private ProductForm $productForm;

    /**
     * StoreAdminController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param StoreAdminService $storeAdminService
     * @param ProductForm<array> $productForm
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        StoreAdminService $storeAdminService,
        ProductForm $productForm
    ) {
        parent::__construct($authService, $logger);
        $this->storeAdminService = $storeAdminService;
        $this->productForm = $productForm;
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        return new ViewModel(['storeProducts' => $this->getStoreAdminService()->getStoreProductsTable()->fetchAll()]);
    }

    /**
     * @return ViewModel<array>
     */
    public function listOrdersAction(): ViewModel
    {
        return new ViewModel(['storeOrders' => $this->getStoreAdminService()->getStoreOrdersTable()->fetchAll()]);
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function viewOrderAction(): ViewModel
    {
        $orderId = (int)$this->params()->fromRoute('id');
        $storeOrdersTable = $this->getStoreAdminService()->getStoreOrdersTable();
        $storeOrder = $storeOrdersTable->getOrder($orderId);

        return new ViewModel(
            [
                'storeOrder' => $storeOrder,
                'orderProduct' => $storeOrder->getProduct(),
            ]
        );
    }

    /**
     * @return Response
     */
    public function deleteProductAction(): Response
    {
        $productId = (int)$this->params()->fromRoute('id');
        $this->getStoreAdminService()->getStoreProductsTable()->deleteProduct($productId);

        return $this->redirect()->toRoute('admin/store-admin');
    }

    /**
     * @return ViewModel<array>
     */
    public function confirmAddProductAction(): ViewModel
    {
        return new ViewModel([
            'name' => $this->params()->fromQuery('name'),
            'productId' => $this->params()->fromQuery('productId')
        ]);
    }

    /**
     * @return Response|ViewModel<array>
     * @throws Exception
     */
    public function newProductAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $form = $this->getProductForm();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if (! $form->isValid()) {
                return new ViewModel([
                    'error' => true,
                    'form'  => $form,
                ]);
            }
            /** @phpstan-ignore-next-line */
            $product = $this->getStoreAdminService()->createProduct($form->getData());

            return $this->redirect()->toRoute(
                'admin/store-admin',
                ['action' => 'confirm-add-product'],
                ['query' => ['productId' => $product->id, 'name' => $product->name]]
            );
        }

        return new ViewModel(['form' => $form]);
    }

    /**
     * @return Response|ViewModel<array>
     * @throws Exception
     */
    public function editProductAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $form = $this->getProductForm();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if (! $form->isValid()) {
                return new ViewModel([
                    'error' => true,
                    'form'  => $form,
                ]);
            }
            $product_id = $post->get('id');
            $product = $this->getStoreAdminService()->getStoreProductsTable()->getProduct($product_id);
            $product->exchangeArray($post);
            $this->getStoreAdminService()->getStoreProductsTable()->saveProduct($product);

            return $this->redirect()->toRoute(
                'admin/store-admin',
                ['action' => 'index']
            );
        }
        $product_id = $this->params()->fromRoute('id');
        $product = $this->getStoreAdminService()->getStoreProductsTable()->getProduct($product_id);
        $form->bind($product);

        return new ViewModel(['form' => $form, 'product_id' => $product_id]);
    }

    /**
     * @return Response
     */
    public function updateOrderStatusAction(): Response
    {
        $orderId = $this->params()->fromRoute('id');
        $newOrderStatus = $this->params()->fromRoute('subaction');
        $storeOrdersTG = $this->getStoreAdminService()->getStoreOrdersTableGateway();
        $storeOrdersTG->update(['status' => $newOrderStatus], ['id' => $orderId]);

        return $this->redirect()->toRoute('admin/store-admin', ['action' => 'viewOrder', 'id' => $orderId]);
    }

    /**
     * @return ProductForm<array>
     */
    private function getProductForm(): ProductForm
    {
        return $this->productForm;
    }

    /**
     * @return StoreAdminService
     */
    private function getStoreAdminService(): StoreAdminService
    {
        return $this->storeAdminService;
    }
}
