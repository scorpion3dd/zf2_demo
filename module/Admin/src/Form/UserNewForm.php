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
 * Class UserNewForm
 * @package Admin\Form
 */
class UserNewForm extends Form
{
    /**
     * UserNewForm constructor.
     */
    public function __construct()
    {
        parent::__construct('New User');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'id',
            'attributes' => [
                'type'  => 'hidden',
            ],
        ]);

        $this->add([
            'name' => 'name',
            'attributes' => [
                'type'  => 'text',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Full Name',
            ],
        ]);

        $this->add([
            'name' => 'email',
            'attributes' => [
                'type'  => 'email',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Email',
            ],
        ]);

        $this->add([
            'name' => 'password',
            'attributes' => [
                'type'  => 'password',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Password',
            ],
        ]);

        $this->add([
            'name' => 'confirmPassword',
            'attributes' => [
                'type'  => 'password',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Confirm Password',
            ],
        ]);

        $this->add([
            'name' => 'birthday',
            'attributes' => [
                'type'  => 'date',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Birthday',
            ],
        ]);

        $this->add([
            'name' => 'phone',
            'attributes' => [
                'type'  => 'phone',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Phone',
            ],
        ]);

        $this->add([
            'name' => 'address',
            'attributes' => [
                'type'  => 'text',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Address',
            ],
        ]);

        $this->add([
            'name' => 'description',
            'attributes' => [
                'type'  => 'text',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'name' => 'type',
            'attributes' => [
                'type'  => 'number',
                'required' => 'required',
                'min'  => '1',
                'max'  => '5',
                'step' => '1'
            ],
            'options' => [
                'label' => 'Type',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Save'
            ],
        ]);
    }
}
