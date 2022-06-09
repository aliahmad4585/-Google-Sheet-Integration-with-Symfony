<?php

namespace App\Service\Log;

class GoogleSheetCommandLog extends BaseLogger
{

    /**
     * @param string $message
     * @param array  $context
     */
    public function logExecutionStartMessage(string $message, array $context = [])
    {
        $this->log(BaseLogger::INFO, $message, $context);
    }
}
