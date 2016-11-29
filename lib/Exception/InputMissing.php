<?php declare(strict_types=1);

namespace Exhale\Exception;

/**
* ArgumentErrorException occurs when function parameters were not correct
*/
class ArgumentErrorException extends RuntimeException {
    public function __construct($message = '', $code = 0) {
        return parent::__construct($message, $code);
    }
}
