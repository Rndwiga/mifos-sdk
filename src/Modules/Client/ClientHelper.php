<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 4/8/18
 * Time: 6:01 PM
 */

namespace Rndwiga\Mifos\Module\Client;


use Rndwiga\Mifos\MifosXConnection;

class ClientHelper extends MifosXConnection
{
    public function getClients($getArray = false,$clientId = null,$withAccounts = false,$withIdentifiers = false,$withImages = false){
        if(isset($clientId)){
            $urlSegment = "/clients/". $clientId;
        }else{
            $urlSegment = "/clients/";
        }

        $requestedData = $this->curlGetRequest($urlSegment);

        if ($getArray == true){
            $response =	json_decode($requestedData,true);
        }else{
            $response =	json_decode($requestedData);
        }

        if ($withAccounts == true && isset($clientId)){
            $urlSegment = $urlSegment."/accounts/";
            $accounts = $this->curlGetRequest($urlSegment);

            if ($getArray == true){
                $response['clientAccounts'] = json_decode($accounts,true);
            }else{
                $response->clientAccounts = json_decode($accounts);
            }
        }

        if ($withIdentifiers == true && isset($clientId)){
            $urlSegment = $urlSegment."/identifiers/";
            $identifiers = $this->curlGetRequest($urlSegment);

            if ($getArray == true){
                $response['clientIdentifiers'] = json_decode($identifiers,true);
            }else{
                $response->clientIdentifiers = json_decode($identifiers);
            }
        }

        if ($withImages == true && isset($clientId)){
            $urlSegment = $urlSegment."/images/";

            $images = $this->curlGetRequest($urlSegment);

            if ($getArray == true){
                $response['clientImages'] = json_decode($images,true);
            }else{
                $response->clientImages = json_decode($images);
            }
        }

        return $response;
    }

    public function getClientAccountsOverview($getArray = false, $clientId=null, $loanAccount =false, $savingsAccount=false){
        if(isset($clientId)){
            $urlSegment = "/clients/". $clientId . "/accounts";
        }else{
            return false;
        }

        if ($loanAccount == true && $savingsAccount== false){
            $urlSegment = $urlSegment."?fields=loanAccounts";
        }elseif($loanAccount == false && $savingsAccount== true){
            $urlSegment = $urlSegment."?fields=savingsAccounts";
        }elseif ($loanAccount == true && $savingsAccount== true){
            $urlSegment = $urlSegment."?fields=loanAccounts,savingsAccounts";
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