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
 * Class SendMessageForm
 * @package Admin\Form
 */
class SendMessageForm extends Form
{
    /**
     * SendMessageForm constructor.
     */
    public function __construct()
    {
        parent::__construct('SendMessageForm');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'message',
            'attributes' => [
                'class' => 'form-control',
                'type'  => 'text',
                'id' => 'messageText',
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
        ]);

        $this->add([
            'name' => 'refresh',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'button',
                'id' => 'btnRefresh',
                'value' => 'Refresh'
            ],
        ]);
    }
}
