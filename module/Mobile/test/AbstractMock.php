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

namespace MobileTest;

use Exception;
use Zend\Db\Adapter\Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class AbstractMock
 * @package MobileTest
 */
abstract class AbstractMock extends AbstractHttpControllerTestCase
{
    public ServiceLocatorInterface $serviceManager;
    private Adapter $db;
    private string $fileName = 'zf2_demo_test.sql';
    private static int $count = 0;
    protected static bool $validation = true;

    /**
     * @return Adapter
     */
    public function getDb(): Adapter
    {
        return $this->db;
    }

    /**
     * @param Adapter $db
     */
    public function setDb(Adapter $db): void
    {
        $this->db = $db;
    }

    /**
     * setUp
     */
    protected function setUp(): void
    {
        // The module configuration should still be applicable for test.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options, etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../config/application.config.test.php',
            $configOverrides
        ));

        putenv('APPLICATION_ENV=TEST');

        $this->serviceManager = $this->getApplication()->getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        $this->prepareDb();

        parent::setUp();
    }

    private function prepareDb(): void
    {
        $testCase = $this->getName();
        if (array_search($testCase, ['testCustomersActionSuccess', 'testGetProductDetailActionSuccess',
            'testGetIndexActionSuccess', 'testGetEditActionSuccess', 'testGetFileDownloadActionSuccess'])) {
            self::$count = 0;
        }
        if (self::$count < 1) {
            self::$count++;
            $this->setDb($this->serviceManager->get('Zend\Db\Adapter\Adapter'));
            $fileFullName = __DIR__ . '/../../../data/db/' . $this->fileName;
            if (file_exists($fileFullName)) {
                $sqlDump = file_get_contents($fileFullName);
                $sql = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $sqlDump);
                $this->db->query($sql, Adapter::QUERY_MODE_EXECUTE);
                $this->echo("created test db for test case - $testCase", false);
            }
        }
    }

    /**
     * https://github.com/xvoland/html-validate
     * @param string $html
     * @param bool $cond
     * @param string $output
     *
     * @return bool
     * @throws Exception
     */
    public function HTMLValidate(string $html, bool $cond = true, string $output = 'text'): bool
    {
        if (empty($html)) {
            self::assertEmpty($html, self::isEmpty());
        }
        if (! is_string($html)) {
            throw new Exception('string', 1);
        }
        $_output = ['xhtml', 'html', 'xml', 'json', 'text'];
        if (! is_string($output) || ! in_array($output, $_output)) {
            throw new Exception('string - text/xhtml/html/xml/json', 2);
        }

        $curlOpt = [
            CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
            CURLOPT_URL            => 'https://html5.validator.nu/',
            CURLOPT_PORT           => null,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => ['out'     => $output, 'content' => $this->makeHTMLBody($html)]
        ];
        $curl = curl_init();
        curl_setopt_array($curl, $curlOpt);
        if (! $response = curl_exec($curl)) {
            if (self::$validation) {
                $this->echo(sprintf('Can\'t check validation. cURL returning error %s', trigger_error(curl_error($curl))));
                self::$validation = false;
            }
        }
        curl_close($curl);
        if (stripos($response, 'Error') !== false || stripos($response, 'Warning') !== false) {
            if ($cond) {
                self::fail($response);
            }
        }

        return true;
    }

    /**
     * @param string $text
     * @param bool $cond
     */
    private function echo(string $text, bool $cond = true): void
    {
        if ($cond) {
            echo "\n" . $text . "\n";
        }
    }

    /**
     * @param string $isHTML
     *
     * @return string
     */
    private function makeHTMLBody(string $isHTML): string
    {
        if (stripos($isHTML, 'html>') === false) {
            return '<!DOCTYPE html><html><head><meta charset=utf-8 /><title></title></head><body>'.$isHTML.'</body></html>';
        } else {
            return $isHTML;
        }
    }

    /**
     * @return array<array>
     */
    public function getConfig(): array
    {
        return $this->serviceManager->get('config');
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function trim(string $html): string
    {
        return str_replace([" ", "\r\n", "\r", "\n"], '', $html);
    }
}
