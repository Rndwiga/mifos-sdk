<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 4/8/18
 * Time: 4:39 PM
 */

namespace Rndwiga\Mifos\Modules\Office;


use Rndwiga\Mifos\MifosXConnection;

class OfficeHelper extends MifosXConnection
{
    public function getOffice($getArray = false,$officeId = null){
        //TODO::cache this data for at-least 60 minutes
        if(isset($officeId)){
            $urlSegment = "/offices/". $officeId;
        }else{
            $urlSegment = "/offices/";
        }
        if ($getArray == true){
            return	json_decode($this->curlGetRequest($urlSegment),true);
        }else{
            return	json_decode($this->curlGetRequest($urlSegment));
        }
    }

}