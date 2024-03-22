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

namespace Admin\Helpers;

use Exception;
use MongoDB\Driver\Manager;
use Zend\Log\Formatter\Base;
use Zend\Log\Logger;
use Zend\Log\Writer\Db;
use Zend\Log\Writer\Mock;
use Zend\Log\Writer\MongoDB;
use Zend\Log\Writer\Stream;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * LoggerHelpers
 * @package Admin\Helpers
 */
class LoggerHelpers
{
    const PATH = '/../../../../data/logs/';

    /**
     * https://framework.zend.com/blog/2017-09-12-zend-log.html
     * https://framework.zend.com/manual/2.1/en/modules/zend.log.writers.html
     * https://docs.zendframework.com/zend-log/writers/
     * @param ServiceLocatorInterface $sm
     *
     * @return Logger
     */
    public static function prepearLogger(ServiceLocatorInterface $sm): Logger
    {
        $logger = new Logger;
        $env = getenv('APPLICATION_ENV');
        if (! empty($env) && $env == 'TEST') {
            $mock = new Mock;
            $logger->addWriter($mock);
        } else {
            $logger->addWriter(self::prepearWriterFileAll());
            $logger->addWriter(self::prepearWriterFile());
            $logger->addWriter(self::prepearWriterDb($sm));
            try {
//                $logger->addWriter(self::prepearWriterMongo());
            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }
        Logger::registerErrorHandler($logger);
        Logger::registerExceptionHandler($logger);

        return $logger;
    }

    /**
     * @return Stream
     */
    private static function prepearWriterFile(): Stream
    {
        return new Stream(__DIR__ . self::PATH . date('Y-m-d') . '.log');
    }

    /**
     * @return Stream
     */
    private static function prepearWriterFileAll(): Stream
    {
        return new Stream(__DIR__ . self::PATH . 'logfile.txt');
    }

    /**
     * @param ServiceLocatorInterface $sm
     *
     * @return Db
     */
    private static function prepearWriterDb(ServiceLocatorInterface $sm): Db
    {
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        $mapping = [
            'timestamp' => 'date',
            'priority'  => 'type',
            'message'   => 'event',
            'extra'   => 'e',
        ];
        $writerDb = new Db($dbAdapter, 'log', $mapping);
        $formatter = new Base();
        $formatter->setDateTimeFormat('Y-m-d H:i:s');
        $writerDb->setFormatter($formatter);

        return $writerDb;
    }

    /**
     * @return MongoDB
     */
    private static function prepearWriterMongo(): MongoDB
    {
        /** @phpstan-ignore-next-line */
        return new MongoDB(new Manager(), getenv('MONGO_CONNECT_DB'), getenv('MONGO_CONNECT_COLLECTION'), []);
    }
}
