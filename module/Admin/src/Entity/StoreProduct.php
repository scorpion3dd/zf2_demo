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

namespace Admin\Entity;

use Zend\Stdlib\ParametersInterface;

/**
 * Class StoreProduct
 *
 * @package Admin\Entity
 */
class StoreProduct extends AbstractEntity
{
    public int $id;
    public string $name;
    public string $desc;
    public int $cost;

    /**
     * StoreProduct constructor.
     */
    public function __construct()
    {
        $this->exchangeArray([]);
    }

    /**
     * @param ParametersInterface<array>|array<array> $data
     */
    public function exchangeArray($data): void
    {
        $this->id = (! empty($data['id'])) ? $data['id'] : 0;
        $this->name = (! empty($data['name'])) ? $data['name'] : '';
        $this->desc = (! empty($data['desc'])) ? $data['desc'] : '';
        $this->cost = (! empty($data['cost'])) ? $data['cost'] : 0;
    }
}
