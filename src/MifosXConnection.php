<?php

namespace Rndwiga\Mifos;

class MifosXConnection {

    protected $baseUrl;
    protected $apiKey;
    protected $tenantHeader;

    public function __construct()
    {
        $this->baseUrl = $this->getBaseUrl();
        $this->apiKey = $this->getApiKey();
        $this->tenantHeader = $this->getTenantHeader();

    }

    private function getBaseUrl(){
        if (env('MIFOS_SERVER_TYPE') == 'live'){
            return env('MIFOS_LIVE_URL');
        }else{
            return env('MIFOS_DEMO_URL');
        }

    }

    private function getApiKey(){
        if (env('MIFOS_SERVER_TYPE') == 'live'){
            return env('MIFOS_LIVE_AUTHORIZATION');
        }else{
            return env('MIFOS_DEMO_AUTHORIZATION');
        }
    }

    private function getTenantHeader(){
        if (env('MIFOS_SERVER_TYPE') == 'live'){
            return env('MIFOS_LIVE_TENANT');
        }else{
            return env('MIFOS_DEMO_TENANT');
        }
    }

    public function curlGetRequest($urlOption){
        $curl = curl_init();
        curl_setopt_array($curl, array(
         // CURLOPT_PORT => "8443",
          CURLOPT_URL => $this->baseUrl . $urlOption ,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYPEER => false, //turn this off when going live.
            CURLOPT_SSL_VERIFYHOST => FALSE,
          CURLOPT_HTTPHEADER => array(
                    $this->apiKey,
                    "cache-control: no-cache",
                    "content-type: application/json",
                    $this->tenantHeader
                ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
            if ($err) {
              return "cURL Error #:" . $err; //build a function that notifies the admin of the failure
            } else {
              return $response;
            }
    }

    /** Tis function is used for making various write requests to te server
     * @param $urlSegment
     * @param $data
     * @param string $httpRequestMethod >> example>> PUT, POST, DELETE
     * @return mixed|string
     */
    public function curlLoginRequest($urlSegment, $httpRequestMethod = "POST"){
        $curl = curl_init();
        curl_setopt_array($curl, array(
         // CURLOPT_PORT => "8443",
          CURLOPT_URL => $this->baseUrl . $urlSegment,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $httpRequestMethod,
          CURLOPT_SSL_VERIFYPEER => false, //turn this off when going live
          CURLOPT_SSL_VERIFYHOST => FALSE,
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
              $this->tenantHeader
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
          $response = "cURL Error #:" . $err;
          return $response;
        } else {
          return $response;
        }
    }

    public function curlMakeRequest($urlSegment,$data,$httpRequestMethod = "POST"){
        $curl = curl_init();
        curl_setopt_array($curl, array(
         // CURLOPT_PORT => "8443",
          CURLOPT_URL => $this->baseUrl . $urlSegment,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $httpRequestMethod,
          CURLOPT_SSL_VERIFYPEER => false, //turn this off when going live
          CURLOPT_SSL_VERIFYHOST => FALSE,
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
              $this->apiKey,
            "cache-control: no-cache",
            "content-type: application/json",
              $this->tenantHeader
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $response = "cURL Error #:" . $err;
          return $response;
        } else {
          return $response;
        }
    }

    /** Tis metod is used for sendin file uploads to te server
     * @param $urlSegment
     * @param $fileData
     * @return mixed|string
     */
    public function curlUploadFile($urlSegment,$fileData){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->baseUrl . $urlSegment,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_POST => true,
          CURLOPT_SSL_VERIFYPEER => false, //turn this off when going live
            CURLOPT_SSL_VERIFYHOST => FALSE,
          CURLOPT_POSTFIELDS => $fileData,
          CURLOPT_HTTPHEADER => array(
                 $this->apiKey,
                "cache-control: no-cache",
                "content-type: multipart/form-data",
                $this->tenantHeader
              ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
          $response = "cURL Error #:" . $err;
          return $response;
        } else {
          return $response;
        }
    }

}
