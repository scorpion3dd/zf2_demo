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

/**
 * Class Html5Form
 * @package Admin\Form
 */
class Html5Form extends Form
{
    /**
     * Html5Form constructor.
     */
    public function __construct()
    {
        parent::__construct('Html5');
        $this->setAttribute('method', 'post');

        $dateTime = new Element\DateTime('element-date-time');
        $dateTime->setLabel('Date/Time Element')
            ->setAttributes([
                'min'  => '2000-01-01T00:00Z',
                'max'  => '2080-01-01T00:00Z',
                'step' => '1',
            ]);
        $this->add($dateTime);

        $dateTime = new Element\DateTimeLocal('element-date-time-local');
        $dateTime->setLabel('Date/Time Local Element')
            ->setAttributes([
                'min'  => '2000-01-01T00:00',
                'max'  => '2080-01-01T00:00',
                'step' => '1',
            ]);
        $this->add($dateTime);

        $time = new Element\Time('element-time');
        $time->setLabel('Time Element');
        $this->add($time);

        $date = new Element\Date('element-date');
        $date->setLabel('Date Element')
            ->setAttributes([
                'min'  => '2000-01-01',
                'max'  => '2080-01-01',
                'step' => '1',
            ]);
        $this->add($date);

        $week = new Element\Week('element-week');
        $week->setLabel('Week Element');
        $this->add($week);

        $month = new Element\Month('element-month');
        $month->setLabel('Month Element');
        $this->add($month);

        $email = new Element\Email('element-email');
        $email->setLabel('Email Element');
        $this->add($email);

        $url = new Element\Url('element-url');
        $url->setLabel('URL Element');
        $this->add($url);

        $number = new Element\Number('element-number');
        $number->setLabel('Number Element')
            ->setAttributes([
                'min'  => '10',
                'max'  => '150',
                'step' => '1',
            ]);
        $this->add($number);

        $range = new Element\Range('element-range');
        $range->setLabel('Range Element')
            ->setAttributes([
                'min'  => '10',
                'max'  => '150',
                'step' => '1',
            ]);
        $this->add($range);

        $color = new Element\Color('element-color');
        $color->setLabel('Color Element');
        $this->add($color);

        $submit = new Element\Submit('element-submit');
        $submit->setValue('Submit');
        $this->add($submit);
    }
}
