<?php

namespace Crabstudio\BaoKim\Exception;

use Exception;

class MissingSecurePassException extends Exception
{

    /**
     * Constructor
     *
     * @param string $message If no message is given 'Missing Secure Pass' will be the message
     * @param int $code Status code, defaults to 403
     */
    public function __construct($message = null, $code = 403)
    {
        if (empty($message)) {
            $message = __('Missing Secure Pass. Please put BaoKim.secure_pass to Configure');
        }
        parent::__construct($message, $code);
    }
}
