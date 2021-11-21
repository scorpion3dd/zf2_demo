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
 * Class RegisterForm
 * @package Admin\Form
 */
class RegisterForm extends Form
{
    /**
     * RegisterForm constructor.
     */
    public function __construct()
    {
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add([
            'name' => 'name',
            'attributes' => [
                'id'  => 'name',
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
                'id'  => 'email',
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
                'id'  => 'password',
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
                'id'  => 'confirmPassword',
                'type'  => 'password',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Confirm Password',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Register'
            ],
        ]);
    }
}
