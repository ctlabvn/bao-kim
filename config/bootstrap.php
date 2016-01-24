<?php

use Cake\Core\Configure;
use Crabstudio\BaoKim\Exception\MissingMerchantException;
use Crabstudio\BaoKim\Exception\MissingSecurePassException;

if(!Configure::read('BaoKim.merchant_id')) {
	throw new MissingMerchantException();
}
if(!Configure::read('BaoKim.secure_pass')) {
	throw new MissingSecurePassException();
}
if(!Configure::read('BaoKim.business')) {
	throw new MissingSecurePassException();
}