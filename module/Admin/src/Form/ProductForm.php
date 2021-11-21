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
 * Class ProductForm
 * @package Admin\Form
 */
class ProductForm extends Form
{
    /**
     * ProductForm constructor.
     */
    public function __construct()
    {
        parent::__construct('ProductForm');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'id',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'hidden',
                'id' => 'id',
                'required' => 'required'
            ],
            'options' => [
                'label' => ' ',
            ],
        ]);

        $this->add([
            'name' => 'name',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'text',
                'id' => 'name',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name' => 'desc',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'textarea',
                'id' => 'desc',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'name' => 'cost',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'number',
                'min'  => '1',
                'id' => 'cost',
                'value' => '1',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Cost',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Save'
            ],
            'options' => [
                'label' => ' ',
            ],
        ]);
    }
}
