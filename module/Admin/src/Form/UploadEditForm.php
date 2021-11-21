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
 * Class UploadEditForm
 * @package Admin\Form
 */
class UploadEditForm extends Form
{
    /**
     * UploadEditForm constructor.
     */
    public function __construct()
    {
        parent::__construct('Upload');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->add([
            'name' => 'id',
            'attributes' => [
                'type'  => 'hidden',
            ],
            'options' => [
                'label' => 'File id',
            ],
        ]);

        $this->add([
            'name' => 'label',
            'attributes' => [
                'type'  => 'text',
            ],
            'options' => [
                'label' => 'File Description',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'class' => 'ui-button',
                'type'  => 'submit',
                'value' => 'Update Document'
            ],
        ]);
    }
}
