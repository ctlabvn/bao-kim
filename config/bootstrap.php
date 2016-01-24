<?php

use Cake\Core\Configure;
use Crabstudio\Exception\MissingMerchantException;
use Crabstudio\Exception\MissingSecurePassException;

if(!Configure::read('BaoKim.merchant_id')) {
	throw new MissingMerchantException();
}
if(!Configure::read('BaoKim.secure_pass')) {
	throw new MissingSecurePassException();
}