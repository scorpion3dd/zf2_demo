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

/**
 * Class AbstractEntity
 * @package Admin\Entity
 */
class AbstractEntity
{
    /**
     * @return array<array>
     */
    public function getArrayCopy(): array
    {
        return get_object_vars($this);
    }
}
