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
 * Class UploadShareForm
 * @package Admin\Form
 */
class UploadShareForm extends Form
{
    /**
     * UploadShareForm constructor.
     */
    public function __construct()
    {
        parent::__construct('UploadShare');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add([
            'name' => 'upload_id',
            'attributes' => [
                'type'  => 'hidden',
            ],
            'options' => [
                'label' => 'Upload',
            ],
        ]);

        $this->add([
            'name' => 'user_id',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => [
                'type'  => 'select',
            ],
            'options' => [
                'label' => 'User',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Add Share'
            ],
        ]);
    }
}
