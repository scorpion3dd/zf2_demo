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
 * Class GoogleUploadPhotoForm
 * @package Admin\Form
 */
class GoogleUploadPhotoForm extends Form
{
    /**
     * GoogleUploadPhotoForm constructor.
     */
    public function __construct()
    {
        parent::__construct('Google Upload Photo');
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
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'name' => 'description',
            'attributes' => [
                'id'  => 'description',
                'type'  => 'text',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'name' => 'fileupload',
            'attributes' => [
                'id'  => 'fileupload',
                'type'  => 'file',
            ],
            'options' => [
                'label' => 'File Upload',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Upload Now'
            ],
        ]);
    }
}
