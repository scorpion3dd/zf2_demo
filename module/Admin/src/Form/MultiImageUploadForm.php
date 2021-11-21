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
use Zend\Form\Element;
use Zend\InputFilter;

/**
 * Class MultiImageUploadForm
 * @package Admin\Form
 */
class MultiImageUploadForm extends Form
{
    /**
     * MultiImageUploadForm constructor.
     * @param null $name
     * @param array<array> $options
     */
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * addElements
     */
    public function addElements(): void
    {
        $imageupload = new Element\File('imageupload');
        $imageupload->setLabel('Image Upload')
            ->setAttribute('id', 'imageupload')
            ->setAttribute('multiple', true);
        $this->add($imageupload);

        $submit = new Element\Submit('submit');
        $submit->setValue('Upload Now');
        $this->add($submit);
    }

    /**
     * addInputFilter
     */
    public function addInputFilter(): void
    {
        $inputFilter = new InputFilter\InputFilter();
        $fileInput = new InputFilter\FileInput('imageupload');
        $fileInput->setRequired(true);
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            [
                'target' => './data/images/temp.jpg',
                'randomize' => true
            ]
        );
        $inputFilter->add($fileInput);
        $this->setInputFilter($inputFilter);
    }
}
