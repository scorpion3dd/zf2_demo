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
 * Class GoogleNewAlbumForm
 * @package Admin\Form
 */
class GoogleNewAlbumForm extends Form
{
    /**
     * GoogleNewAlbumForm constructor.
     */
    public function __construct()
    {
        parent::__construct('New Google Album');
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
                'label' => 'Name',
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
