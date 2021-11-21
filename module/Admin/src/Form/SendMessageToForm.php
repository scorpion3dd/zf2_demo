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
 * Class SendMessageToForm
 * @package Admin\Form
 */
class SendMessageToForm extends Form
{
    /**
     * SendMessageToForm constructor.
     */
    public function __construct()
    {
        parent::__construct('SendMessageToForm');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'toUserId',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => [
                'class' => 'form-select',
                'type'  => 'select',
            ],
            'options' => [
                'label' => 'To User',
            ],
        ]);

        $this->add([
            'name' => 'messageSubject',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'text',
                'id' => 'messageSubject',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Subject',
            ],
        ]);

        $this->add([
            'name' => 'message',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'textarea',
                'id' => 'message',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Message',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Send'
            ],
            'options' => [
                'label' => 'Send',
            ],
        ]);
    }
}
