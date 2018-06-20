<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 4/21/18
 * Time: 12:44 PM
 */

namespace Rndwiga\Mifos\Modules\Configuration;


use Rndwiga\Mifos\MifosXConnection;

class PaymentTypeHelper extends MifosXConnection
{
    public function getPaymentTypes($getArray = false,$paymentTypeId = null){
        //TODO::cache this data for at-least 60 minutes
        if(isset($paymentTypeId)){
            $urlSegment = "/paymenttypes/". $paymentTypeId;
        }else{
            $urlSegment = "/paymenttypes/";
        }
        if ($getArray == true){
            return	json_decode($this->curlGetRequest($urlSegment),true);
        }else{
            return	json_decode($this->curlGetRequest($urlSegment));
        }
    }
}