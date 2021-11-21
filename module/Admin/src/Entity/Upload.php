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
 * Class Upload
 * @package Admin\Entity
 */
class Upload extends AbstractEntity
{
    public int $id;
    public string $filename;
    public string $label;
    public int $user_id;

    /**
     * @param ParametersInterface<array>|array<string, int|string> $data
     */
    public function exchangeArray($data): void
    {
        $this->id = (! empty($data['id'])) ? $data['id'] : 0;
        $this->filename = (! empty($data['filename'])) ? $data['filename'] : null;
        $this->label = (! empty($data['label'])) ? $data['label'] : null;
        $this->user_id = (! empty($data['user_id'])) ? $data['user_id'] : 0;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }
}
