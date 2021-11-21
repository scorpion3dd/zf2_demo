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
 * Class SearchForm
 * @package Admin\Form
 */
class SearchForm extends Form
{
    /**
     * SearchForm constructor.
     */
    public function __construct()
    {
        parent::__construct('Login');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'query',
            'attributes' => [
                'type'  => 'text',
                'id' => 'queryText',
                'required' => 'required',
            ],
            'options' => [
                'label' => 'Search String',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'class' => 'ui-button',
                'value' => 'Search'
            ],
            'options' => [
                'label' => ' ',
            ],
        ]);
    }
}
