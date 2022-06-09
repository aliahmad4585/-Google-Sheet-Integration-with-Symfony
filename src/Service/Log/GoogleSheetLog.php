<?php

namespace App\Service\Log;

class GoogleSheetLog extends BaseLogger
{

    /**
     * @param string $message
     * @param array  $context
     */
    public function logGoogleClientError(string $message, array $context = [])
    {
        $this->log(BaseLogger::CRITICAL, $message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function logWriteDateOnSheetError(string $message, array $context = [])
    {
        $this->log(BaseLogger::CRITICAL, $message, $context);
    }
}
