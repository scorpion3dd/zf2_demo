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

use Admin\Entity\Upload;
use MobileTest\AbstractMock;

/**
 * Class UploadTest
 * @package AdminTest\Entity
 */
class UploadTest extends AbstractMock
{
    public function testExchangeArray()
    {
        $id = 11;
        $filename = 'table.jpg';
        $label = 'Office table';
        $user_id = 1050;
        $storeProduct = new Upload();
        $storeProduct->exchangeArray([
            'id' => $id,
            'filename' => $filename,
            'label' => $label,
            'user_id' => $user_id,
        ]);
        $this->assertSame($id, $storeProduct->id);
        $this->assertSame($filename, $storeProduct->filename);
        $this->assertSame($label, $storeProduct->label);
        $this->assertSame($user_id, $storeProduct->user_id);
    }
}
