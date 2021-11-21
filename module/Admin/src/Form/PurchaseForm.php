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

namespace Admin\Form;

use Zend\Form\Form;

/**
 * Class PurchaseForm
 * @package Admin\Form
 */
class PurchaseForm extends Form
{
    /**
     * PurchaseForm constructor.
     */
    public function __construct()
    {
        parent::__construct('PurchaseForm');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'qty',
            'attributes' => [
                'type'  => 'number',
                'id' => 'qty',
                'value' => '1',
                'min' => '1',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Quantity',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Purchase'
            ],
        ]);

        $this->add([
            'name' => 'store_product_id',
            'attributes' => [
                'type'  => 'hidden'
            ],
        ]);
    }
}
