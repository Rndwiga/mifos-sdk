<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 3/24/18
 * Time: 4:23 PM
 */

namespace Rndwiga\Mifos\Modules\Configuration;

use Rndwiga\Mifos\MifosXConnection;

;


class DropDownHelper extends MifosXConnection
{

    /**
     * @param null $codeValues
     * @param null $value
     * @param bool $getArray
     * @return mixed -->an array of objects e.g [object, object] or array
     */
    public function getDropDownValues($codeValues = null, $value = null,$getArray= false)
    {
        $urlSegment = "/codes/";
        if(isset($codeValues) && !isset($value)){
            $urlSegment = "/codes/".$codeValues."/codevalues/";
        }elseif (isset($codeValues) && isset($value)){
            $urlSegment = "/codes/".$codeValues."/codevalues/".$value;
        }
        if ($getArray == true){
            return	json_decode($this->curlGetRequest($urlSegment),true);
        }else{
            return	json_decode($this->curlGetRequest($urlSegment));
        }
    }
}