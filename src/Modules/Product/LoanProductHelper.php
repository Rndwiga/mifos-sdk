<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 7/7/18
 * Time: 9:36 PM
 */

namespace Rndwiga\Mifos\Modules\Product;


use Rndwiga\Mifos\MifosXConnection;

class LoanProductHelper extends MifosXConnection
{
    public function listLoanProducts($getArray = false, int $loanProductId = null , array $options = []){

        if ($loanProductId){
            $urlSegment = "/loanproducts/".$loanProductId;
        }else{
            $urlSegment = "/loanproducts/";
        }


        if (count($options) > 0){
            $urlSegment = $urlSegment."?fields=".implode(',',array_values($options));
        }

        $requestedData = $this->curlGetRequest($urlSegment);

        if ($getArray == true){
            $response =	json_decode($requestedData,true);
        }else{
            $response =	json_decode($requestedData);
        }

        return $response;
    }
}