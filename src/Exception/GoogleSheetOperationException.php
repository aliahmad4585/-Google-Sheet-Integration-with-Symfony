<?php

namespace App\Exception;

use App\Interface\Exception\MessageExceptionInterface;

class GoogleSheetOperationException extends \Exception implements MessageExceptionInterface
{
}
