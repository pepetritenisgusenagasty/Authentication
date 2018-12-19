<?php

namespace App\validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class EmailAvaliableException extends ValidationException
{
	public static $defaultTemplates = [
          self::MODE_DEFAULT => [
            
            self::STANDARD =>'Email already exists',
          ],
	];
}

