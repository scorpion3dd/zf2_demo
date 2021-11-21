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

namespace Admin\Service;

use Admin\Entity\StoreOrder;
use Admin\Entity\StoreProduct;
use Admin\Repository\StoreOrderRepository;
use Admin\Repository\StoreProductRepository;
use Exception;
use http\Client\Request;
use stdClass;
use Zend\Config\Config;
use Zend\Db\TableGateway\TableGateway;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Session\Container;

/**
 * Class StoreService
 * @package Admin\Service
 */
class StoreService extends AbstractService
{
    const PAYMENT_CONFIRM_URL = 'http://comm-app.local/admin/store/paymentConfirm';
    const PAYMENT_CANCEL_URL = 'http://comm-app.local/admin/store/paymentCancel';
    const PAYMENT_EXPRESS_CHECKOUT_URL = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=';

    private StoreOrderRepository $storeOrdersTable;
    private StoreProductRepository $storeProductsTable;
    private TableGateway $storeOrdersTableGateway;

    /**
     * StoreService constructor.
     * @param array<array> $config
     * @param StoreOrderRepository $storeOrdersTable
     * @param StoreProductRepository $storeProductsTable
     * @param TableGateway $storeOrdersTableGateway
     */
    public function __construct(
        array                  $config,
        StoreOrderRepository   $storeOrdersTable,
        StoreProductRepository $storeProductsTable,
        TableGateway           $storeOrdersTableGateway
    ) {
        parent::__construct($config);
        $this->storeOrdersTable = $storeOrdersTable;
        $this->storeProductsTable = $storeProductsTable;
        $this->storeOrdersTableGateway = $storeOrdersTableGateway;
    }

    /**
     * @return StoreOrderRepository
     */
    public function getStoreOrdersTable(): StoreOrderRepository
    {
        return $this->storeOrdersTable;
    }

    /**
     * @return StoreProductRepository
     */
    public function getStoreProductsTable(): StoreProductRepository
    {
        return $this->storeProductsTable;
    }

    /**
     * @return TableGateway
     */
    public function getStoreOrdersTableGateway(): TableGateway
    {
        return $this->storeOrdersTableGateway;
    }

    /**
     * @param array<array> $data
     *
     * @return StoreProduct
     * @throws Exception
     */
    public function createProduct(array $data): StoreProduct
    {
        $product = new StoreProduct();
        $product->exchangeArray($data);

        return $this->getStoreProductsTable()->saveProduct($product);
    }

    /**
//     * @return \SpeckPaypal\Service\Request
     * @return object|null
     */
    public function getPaypalRequest(): ?object
    {
        $config  = $this->getConfig();
//        $paypalConfig = new \SpeckPaypal\Element\Config($config['speck-paypal-api']);
        $paypalConfig = new Config($config['speck-paypal-api']);

        $adapter = new Curl();
        $adapter->setOptions([
            'curloptions' => [CURLOPT_SSL_VERIFYPEER => false,]
        ]);

        $client = new Client;
        $client->setMethod('POST');
        $client->setAdapter($adapter);

        $paypalRequest = null;
        if (class_exists(Request::class)) {
            /** @var object $paypalRequest */
            $paypalRequest = new Request;
            /** @phpstan-ignore-next-line */
            $paypalRequest->setClient($client);
            /** @phpstan-ignore-next-line */
            $paypalRequest->setConfig($paypalConfig);
        }

        return $paypalRequest;
    }

    /**
     * @param StoreOrder $order
     *
     * @return string
     */
    public function getPaypalExpressCheckout(StoreOrder $order): string
    {
        $url = '/admin/store';
        $paypalRequest = $this->getPaypalRequest();
        if ($paypalRequest) {
//        $paymentDetails = new \SpeckPaypal\Element\PaymentDetails(['amt' => $order->total]);
            $paymentDetails = new StdClass();
//        $express = new \SpeckPaypal\Request\SetExpressCheckout(['paymentDetails' => $paymentDetails]);
            /** @var object $express */
            $express = new StdClass();

            /** @phpstan-ignore-next-line */
            $express->setReturnUrl(self::PAYMENT_CONFIRM_URL);
            /** @phpstan-ignore-next-line */
            $express->setCancelUrl(self::PAYMENT_CANCEL_URL);

            /** @phpstan-ignore-next-line */
            $response = $paypalRequest->send($express);
            $token = $response->getToken();

            /** @var object $paypalSession */
            $paypalSession = new Container('paypal');
            /** @phpstan-ignore-next-line */
            $paypalSession->tokenId = $token;
            /** @phpstan-ignore-next-line */
            $paypalSession->orderId = $order->id;

            $url = self::PAYMENT_EXPRESS_CHECKOUT_URL . $token;
        }

        return $url;
    }

    /**
     * @return StoreOrder
     * @throws Exception
     */
    public function paymentConfirm(): StoreOrder
    {
        $orderTable = $this->getStoreOrdersTable();

        //To capture Payer Information from PayPal
        /** @var object $paypalSession */
        $paypalSession = new Container('paypal');
        $paypalRequest = $this->getPaypalRequest();

        if (class_exists(\SpeckPaypal\Request\GetExpressCheckoutDetails::class)) {
            /** @var object $expressCheckoutInfo */
            $expressCheckoutInfo = new stdClass();
            /** @phpstan-ignore-next-line */
            $expressCheckoutInfo->setToken($paypalSession->tokenId);
            /** @phpstan-ignore-next-line */
            $response = $paypalRequest->send($expressCheckoutInfo);

            //To capture express payment
            /** @phpstan-ignore-next-line */
            $order = $orderTable->getOrder((int)$paypalSession->orderId);
//        $paymentDetails = new \SpeckPaypal\Element\PaymentDetails(['amt' => $order->total]);
            $paymentDetails = new stdClass();

            $token = $response->getToken();
            $payerId = $response->getPayerId();

//        $captureExpress = new \SpeckPaypal\Request\DoExpressCheckoutPayment([
//            'token'             => $token,
//            'payerId'           => $payerId,
//            'paymentDetails'    => $paymentDetails
//        ]);
            $captureExpress = new stdClass();
            /** @phpstan-ignore-next-line */
            $captureResponse = $paypalRequest->send($captureExpress);

            //To Save Order Information
            $order->first_name = $response->getFirstName();
            $order->last_name = $response->getLastName();
            $order->ship_to_street = $response->getShipToStreet();
            $order->ship_to_city = $response->getShipToCity();
            $order->ship_to_state = $response->getShipToState();
            $order->ship_to_zip = $response->getShipToZip();
            $order->email = $response->getEmail();
            /** @phpstan-ignore-next-line */
            $order->store_order_id = $paypalSession->orderId;
            $order->status = 'completed';

            $orderTable->saveOrder($order);

            /** @phpstan-ignore-next-line */
            $paypalSession->orderId = null;
            /** @phpstan-ignore-next-line */
            $paypalSession->tokenId = null;
        } else {
            $order = new StoreOrder(new StoreProduct());
        }

        return $order;
    }
}
