<?php

namespace App\Service\Log;

use Psr\Log\LoggerInterface;

use Monolog\Logger;

class BaseLogger
{
    /**
     * Detailed debug information
     *
     * @deprecated Use \Monolog\Level::Debug
     */
    public const DEBUG = 100;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     *
     * @deprecated Use \Monolog\Level::Info
     */
    public const INFO = 200;

    /**
     * Uncommon events
     *
     * @deprecated Use \Monolog\Level::Notice
     */
    public const NOTICE = 250;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     *
     * @deprecated Use \Monolog\Level::Warning
     */
    public const WARNING = 300;

    /**
     * Runtime errors
     *
     * @deprecated Use \Monolog\Level::Error
     */
    public const ERROR = 400;

    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @deprecated Use \Monolog\Level::Critical
     */
    public const CRITICAL = 500;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     *
     * @deprecated Use \Monolog\Level::Alert
     */
    public const ALERT = 550;

    /**
     * Urgent alert.
     *
     * @deprecated Use \Monolog\Level::Emergency
     */
    public const EMERGENCY = 600;

     /**
     * Resource not found.
     *
     * @deprecated Use \Monolog\Level::NOTFOUND
     */
    public const NOTFOUND = 400;

    /**
     * Monolog API version
     *
     * This is only bumped when API breaks are done and should
     * follow the major version of the library
     */
    public const API = 3;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $level
     * @param string $message 
     * @param array  $context
     */
    public function log(string $level, string $message, array $context)
    {
       $this->logger->addRecord($level, $message, $context);
    }


    /**
     * @param string $message
     * @param array  $context
     */
    public function logError(string $message, array $context = [])
    {
        $this->log(BaseLogger::ERROR, $message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function logWarning(string $message, array $context = [])
    {
        $this->log(BaseLogger::WARNING, $message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function logNotice(string $message, array $context = [])
    {
        $this->log(BaseLogger::NOTICE, $message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function logInfo(string $message, array $context = [])
    {
        $this->log(BaseLogger::INFO, $message, $context);
    }
}