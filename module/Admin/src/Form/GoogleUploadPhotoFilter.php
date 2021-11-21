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
 * Class GoogleUploadPhotoFilter
 * @package Admin\Form
 */
class GoogleUploadPhotoFilter extends InputFilter
{
    /**
     * GoogleUploadPhotoFilter constructor.
     */
    public function __construct()
    {
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
                        'max'      => 240,
                    ],
                ],
            ],
        ]);
    }
}
