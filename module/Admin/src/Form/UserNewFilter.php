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
 * Class UserNewFilter
 * @package Admin\Form
 */
class UserNewFilter extends InputFilter
{
    /**
     * UserNewFilter constructor.
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
            'name'       => 'description',
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
                        'max'      => 1000,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'phone',
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
                        'max'      => 255,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'address',
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
                        'max'      => 255,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'type',
            'required'   => true,
            'filters'    => [
                [
                    'name'    => 'StripTags',
                ],
            ],
            'validators' => [
                [
                    'name'    => 'Between',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                        'max'      => 5,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name'       => 'birthday',
            'required'   => true,
            'filters'    => [
                [
                    'name'    => 'StripTags',
                ],
            ],
            'validators' => [
                [
                    'name'    => 'Date',
                    'options' => [
                        'encoding' => 'UTF-8',
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
