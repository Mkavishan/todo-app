<?php

namespace App\Enums;

enum Messages: string
{
    case SERVER_ERROR = 'Unable to process the request';
    case COMPLETED = 'Task marked as completed';
}
