<?php

namespace Crabstudio\Exception;

use Exception;

class MissingMerchantException extends Exception
{

    /**
     * Constructor
     *
     * @param string $message If no message is given 'Missing Merchant Id' will be the message
     * @param int $code Status code, defaults to 403
     */
    public function __construct($message = null, $code = 403)
    {
        if (empty($message)) {
            $message = __('Missing Merchant Id. Please put BaoKim.merchant_id to Configure');
        }
        parent::__construct($message, $code);
    }
}
