<?php declare(strict_types=1);

namespace Exhale\Exception;

/**
* NotImplementedException occurs when an object or
*/
class NotImplementedException extends RuntimeException {
    public function __construct($message = '', $code = 0) {
        return parent::__construct($message, $code);
    }
}
