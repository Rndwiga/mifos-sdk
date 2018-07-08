<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 7/7/18
 * Time: 7:08 PM
 */

namespace Rndwiga\Mifos\Modules\Saving;


use Rndwiga\Mifos\MifosXConnection;

class SavingHelper extends MifosXConnection
{
    public function listSavingsAccounts($getArray = false, array $options){
        $urlSegment = "/savingsaccounts/";

        if ($options){
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

    public function retrieveSavingsAccount($getArray = false, int $savingsAccountId ,array $options){
        if (!$savingsAccountId){
            return false;
        }else{
            $urlSegment = "/savingsaccounts/".$savingsAccountId;
        }

        if ($options){
            $urlSegment = $urlSegment."?associations=".implode(',',array_values($options));
        }

        $requestedData = $this->curlGetRequest($urlSegment);

        if ($getArray == true){
            $response =	json_decode($requestedData,true);
        }else{
            $response =	json_decode($requestedData);
        }

        return $response;
    }

    public function retrieveSavingsAccountTransaction($getArray = false, int $savingsAccountId){
        if (!$savingsAccountId){
            return false;
        }else{
            $urlSegment = "/savingsaccounts/".$savingsAccountId ."/transactions";
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