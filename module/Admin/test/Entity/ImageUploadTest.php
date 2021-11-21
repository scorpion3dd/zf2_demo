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

namespace AdminTest\Entity;

use Admin\Entity\ImageUpload;
use MobileTest\AbstractMock;

/**
 * Class ImageUploadTest
 * @package AdminTest\Entity
 */
class ImageUploadTest extends AbstractMock
{
    public function testExchangeArray()
    {
        $id = 11;
        $filename = 'php.jpg';
        $thumbnail = 'tn_php.jpg';
        $label = 'php';
        $user_id = 123;
        $storeOrder = new ImageUpload();
        $storeOrder->exchangeArray([
            'id' => $id,
            'filename' => $filename,
            'thumbnail' => $thumbnail,
            'label' => $label,
            'user_id' => $user_id,
        ]);
        $this->assertSame($id, $storeOrder->id);
        $this->assertSame($filename, $storeOrder->filename);
        $this->assertSame($thumbnail, $storeOrder->thumbnail);
        $this->assertSame($label, $storeOrder->label);
        $this->assertSame($user_id, $storeOrder->user_id);
    }
}
