<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 3/24/18
 * Time: 3:43 PM
 */

namespace Rndwiga\Mifos;

use Rndwiga\Mifos\Helpers\MifosUtility;


class DataUpload extends MifosXConnection
{
    /**
     * @param $realFilePath
     * @param $urlSegment >>example "/cct_CashFlowFinancialSummary/500/documents/?tenantIdentifier=kenya"
     * @param $resourceId
     * @return array|mixed|string
     */
    public function sendFile($realFilePath,$urlSegment,$resourceId,$mimeType,$fieldName){
        $info = new \SplFileInfo($realFilePath);
        $filename = $info->getFilename();
        $fileData = [
            "file" => new \CurlFile($realFilePath, $mimeType, $filename),
            "name" => $fieldName,
            "locale" => "en",
            "appTableId" => $resourceId,
        ];
        $feedback = $this->curlUploadFile($urlSegment,$fileData);
        $msg = json_decode($feedback); //decoding the feedback
        if(isset($msg->resourceId)){ //if success
            $feedback = array('status' => "success", 'code' => http_response_code(200));
            return $feedback;
        }else {
            //return the error message
            return $msg;
        }
    }

    /**
     * @param $postData
     * @param $urlSegment >> example >> "/datatables/cct_CashFlowFinancialSummary/2030"
     * @param $httpRequestMethod
     * @return array|mixed|string
     */
    public function sendData($postData,$urlSegment,$httpRequestMethod="POST"){
        $data =  json_encode($postData);

        $feedback = $this->curlMakeRequest($urlSegment,$data,$httpRequestMethod);

        MifosUtility::logInfo($feedback,'send_feedback');

        $msg = json_decode($feedback); //decoding the feedback

        $code = 0;

        isset($msg->httpStatusCode) ? $code = $msg->httpStatusCode : isset($msg->status) && $msg->status == 404 ? MifosUtility::logInfo($feedback,'send_feedback') : $code =0;


        if($code == 403){
            //if the post failed try PUT
            $feedback = $this->curlMakeRequest($urlSegment,$data,"PUT");
            MifosUtility::logInfo($feedback,'executed_put_request');
        }
        //If the posting/updating is a success
        $msg = json_decode($feedback); //decoding the feedback
        if(isset($msg->resourceId)){

            return [
                'status' => 'success',
                'code' => http_response_code(200),
                'data' =>  json_decode($feedback,true)
            ];
        }
    }


}