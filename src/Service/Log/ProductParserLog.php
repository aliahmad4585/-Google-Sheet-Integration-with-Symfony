<?php

namespace App\Service\Log;

class ProductParserLog extends BaseLogger
{
    /**
     * @param string $message
     * @param array  $context
     */
    public function logExceptionOnXmlLoad(string $message, array $context = [])
    {
        $this->log(BaseLogger::CRITICAL, $message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function logExceptionOnProductParse(string $message, array $context = [])
    {
        $this->log(BaseLogger::CRITICAL, $message, $context);
    }
}
