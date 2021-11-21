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

use Admin\Form\PurchaseForm;
use Admin\Service\StoreService;
use Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Admin\Entity\StoreOrder;

/**
 * Class StoreController
 * @package Admin\Controller
 */
class StoreController extends ActionController
{
    /** @var StoreService $storeService */
    private StoreService $storeService;
    /** @var PurchaseForm<array> $purchaseForm */
    private PurchaseForm $purchaseForm;

    /**
     * StoreController constructor.
     * @param Logger $logger
     * @param AuthenticationService $authService
     * @param StoreService $storeService
     * @param PurchaseForm<array> $purchaseForm
     */
    public function __construct(
        Logger $logger,
        AuthenticationService $authService,
        StoreService $storeService,
        PurchaseForm $purchaseForm
    ) {
        parent::__construct($authService, $logger);
        $this->storeService = $storeService;
        $this->purchaseForm = $purchaseForm;
    }

    /**
     * @return ViewModel<array>
     */
    public function indexAction(): ViewModel
    {
        $this->getLogger()->log(Logger::INFO, 'StoreController', ['indexAction']);

        return new ViewModel(['storeProducts' => $this->getStoreService()->getStoreProductsTable()->fetchAll()]);
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function productDetailAction(): ViewModel
    {
        $productId = $this->params()->fromRoute('id');
        $productTable = $this->getStoreService()->getStoreProductsTable();
        $product = $productTable->getProduct($productId);

        $form = $this->getPurchaseForm();
        $form->get('store_product_id')->setValue($product->id);

        return new ViewModel(
            [
                'product' => $product,
                'form' => $form
            ]
        );
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function shoppingCartAction(): ViewModel
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $productId = (int)$request->getPost()->get('store_product_id');
        $quantity = (int)$request->getPost()->get('qty');

        $orderTable = $this->getStoreService()->getStoreOrdersTable();
        $productTable = $this->getStoreService()->getStoreProductsTable();
        $product = $productTable->getProduct($productId);

        $newOrder = new StoreOrder($product);
        $newOrder->setQuantity($quantity);
        $orderId = $orderTable->saveOrder($newOrder);
        $order = $orderTable->getOrder($orderId);

        return new ViewModel(
            [
                'order' => $order,
                'productId' => $order->getProduct()->id,
                'productName' => $order->getProduct()->name,
                'productQty' => $order->qty,
                'unitCost' => $order->getProduct()->cost,
                'total' => $order->total,
                'orderId' => $order->id,
            ]
        );
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function paypalExpressCheckoutAction(): Response
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $orderId = (int)$request->getPost()->get('orderId');

        $orderTable = $this->getStoreService()->getStoreOrdersTable();
        $order = $orderTable->getOrder($orderId);

        return $this->redirect()->toUrl($this->getStoreService()->getPaypalExpressCheckout($order));
    }

    /**
     * @return ViewModel<array>
     * @throws Exception
     */
    public function paymentConfirmAction(): ViewModel
    {
        $order = $this->getStoreService()->paymentConfirm();

        return new ViewModel(
            [
                'storeOrder' => $order,
                'orderProduct' => $order->getProduct(),
            ]
        );
    }

    /**
     * @return ViewModel<array>
     */
    public function paymentCancelAction(): ViewModel
    {
        /** @var object $paypalSession */
        $paypalSession = new Container('paypal');

        $storeOrdersTG = $this->getStoreService()->getStoreOrdersTableGateway();
        /** @phpstan-ignore-next-line */
        $storeOrdersTG->update(['status' => 'cancelled'], ['id' => $paypalSession->orderId]);
        /** @phpstan-ignore-next-line */
        $paypalSession->orderId = null;
        /** @phpstan-ignore-next-line */
        $paypalSession->tokenId = null;

        return new ViewModel();
    }

    /**
     * @return PurchaseForm<array>
     */
    private function getPurchaseForm(): PurchaseForm
    {
        return $this->purchaseForm;
    }

    /**
     * @return StoreService
     */
    private function getStoreService(): StoreService
    {
        return $this->storeService;
    }
}
