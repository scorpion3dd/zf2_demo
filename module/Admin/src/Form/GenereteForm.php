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
 * Class GenereteForm
 * @package Admin\Form
 */
class GenereteForm extends Form
{
    /**
     * GenereteForm constructor.
     */
    public function __construct()
    {
        parent::__construct('Login');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'count',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'number',
                'id' => 'count',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Count',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Generete'
            ],
            'options' => [
                'label' => ' ',
            ],
        ]);
    }
}
