<?php

namespace App\Exception;

use App\Interface\Exception\MessageExceptionInterface;

class GoogleSheetCommandException extends \Exception implements MessageExceptionInterface
{
}
