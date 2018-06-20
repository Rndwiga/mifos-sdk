<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 6/1/18
 * Time: 9:24 PM
 */

namespace Rndwiga\Mifos\src\Modules\Search;


use Rndwiga\Mifos\Helpers\MifosUtility;
use Rndwiga\Mifos\MifosXConnection;
use Rndwiga\Mifos\Module\Client\ClientHelper;

class ClientSearch extends MifosXConnection
{
    /**
     * @param bool $getArray
     * @param null $clientIdentifier
     * @param bool $withClientDetails
     * @return mixed
    [
        {
            "entityId": 5,
            "entityName": "0721207949",
            "entityType": "CLIENTIDENTIFIER",
            "parentId": 9,
            "parentName": "Robert Omwando",
                "entityStatus": {
                "id": 300,
                "code": "clientStatusType.active",
                "value": "Active"
                }
        }
    ]
     *
     */
    public function searchClientIdentifier($getArray = false, $clientIdentifier = null, $withClientDetails = false){
        //https://13.250.138.158/fineract-provider/api/v1/search?query=0721207949&exactMatch=true&resource=clientIdentifiers

        if(isset($clientIdentifier)){
            $urlSegment = "/search?query=". $clientIdentifier . "&exactMatch=true&resource=clientIdentifiers";
        }else{
            $urlSegment = null;
        }
       // return $urlSegment;
        $requestedData = $this->curlGetRequest($urlSegment);

        if ($getArray == true){
            $response =	json_decode($requestedData,true);
        }else{
            $response =	json_decode($requestedData);
        }


        if (count(json_decode($requestedData,true)) == 1){
             $searchedId = (json_decode($requestedData))[0]->parentId;

            if ($withClientDetails == true){
                $client = new ClientHelper();
                $clientData = $client->getClients($getArray,$searchedId,false,$withClientDetails,false);

                if ($getArray == true){
                    $response[0]['clientDetails'] = $clientData;
                }else{
                    $response[0]->clientDetails = $clientData;
                }
            }
        }else{
            MifosUtility::logInfo(json_encode($response),'searched_client','searchClient');
        }

        return $response;
    }
}