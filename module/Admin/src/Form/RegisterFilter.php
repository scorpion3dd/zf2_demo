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

use Zend\InputFilter\InputFilter;

/**
 * Class RegisterFilter
 * @package Admin\Form
 */
class RegisterFilter extends InputFilter
{
    /**
     * RegisterFilter constructor.
     */
    public function __construct()
    {
        $this->add([
            'name'       => 'email',
            'required'   => true,
            'validators' => [
                [
                    'name'    => 'EmailAddress',
                    'options' => [
                        'domain' => true,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'name',
            'required'   => true,
            'filters'    => [
                [
                    'name'    => 'StripTags',
                ],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 2,
                        'max'      => 140,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'password',
            'required'   => true,
        ]);

        $this->add([
            'name'       => 'confirmPassword',
            'required'   => true,
        ]);
    }
}
