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
 * Class ImageUploadForm
 * @package Admin\Form
 */
class ImageUploadForm extends Form
{
    /**
     * ImageUploadForm constructor.
     */
    public function __construct()
    {
        parent::__construct('Upload');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add([
            'name' => 'label',
            'attributes' => [
                'id'  => 'label',
                'type'  => 'text',
            ],
            'options' => [
                'label' => 'Image Description',
            ],
        ]);

        $this->add([
            'name' => 'imageupload',
            'attributes' => [
                'id'  => 'imageupload',
                'type'  => 'file',
            ],
            'options' => [
                'label' => 'Image Upload',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'value' => 'Upload Now'
            ],
        ]);
    }
}
