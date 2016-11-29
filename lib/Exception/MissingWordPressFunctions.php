<?php declare(strict_types=1);

namespace Exhale\Exception;

/**
* WordPressFunctionsNotFound occurs when WordPress core functions are missing
*/
class WordPressFunctionsNotFound extends RuntimeException {
    public function __construct($message = '', $code = 0) {
        return parent::__construct($message, $code);
    }
}
