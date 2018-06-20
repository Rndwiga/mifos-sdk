<?php
/*
    This class has not been tested and is incomplete.
    Its supposed to help in caching of api requests to make the process faster.
*/
namespace Rndwiga\Mifos\Helpers;

use Rndwiga\Mifos\MifosXConnection;

class CacheLibrary extends MifosXConnection
{
    protected $urlOption;

    public function cacheCashflowRequests($urlOption = NULL){
        //this will be the front function for caching and retrieving requests and calling the rest.
        $request = "https://demo.musonisystem.com:8443/api/v1" . $urlOption; //setting the url to fetch data from
        $cachedFile = 'cashFlow' . str_replace("/","_",$urlOption) . time() . '.json' ; //setting the cache file name
        $cachedFileFullPath = './docs/cache/' . $cachedFile ; //setting the full file path
        $cacheTimeOut = 86400;  //setting the expiration time to 24hrs
        //calling function that will either return data from cache file or from a new request
        $data = $this->requestCacheNewData($request, $cachedFileFullPath, $cacheTimeOut);
    }
    protected function requestCacheNewData($url, $cachedFileName, $timeout){
        //Fetch data via new API call if file does not exist or if it is stale.
        if (!file_exists($cachedFileName) || filemtime($cachedFileName) < (time() - $timeout ) ) {
            $callResponse = $this->curlGetRequest($url); //making the api call.
              if($callResponse === false) return false; //If the request fails let the function cacheCashflowRequests handle failure.

                  //creating a temporary file for storing the response
                  $tempFile = tempnam('/tmp', 'CashFlow');
                  $fileHandle = fopen($tempFile, "w");
                  fwrite($fileHandle, $callResponse); //writing the response to temp file.
                  fclose($fileHandle);
                  rename($tempFile, $cachedFileName); //renaming the temp file to the cachedfile name.
      } else {
        //if the cached file exists and its not stale return its content.
          return file_get_contents($cachedFileName);
      }
      //if the call  request for new data went successfully return the result.
      return $callResponse;
}

/*
The function status_code_check returns a Boolean value or exits and displays an error message.
*/
  // Examines Web service response for header codes
function statusCodeCheck($response)
{
    // Get HTTP Status code from the response
    $status_code = array();
    $ok_code = false;
    // Parse first line of header for header code
    preg_match('/\d\d\d/', $response, $status_code);
    // Check the HTTP Status code
    switch( $status_code[0] ) {
        case 200:
            // Success
            $ok_code = true;
            break;
        case 503:
            die('The call to Musoni Services failed and returned 503 error -Servie unavailable-. Hence cant return the data.');
            break;
        case 403:
            die( 'The call to Musoni Services failed and returned 403 error -Forbidden-. Meaning the plugin does not have permission to access
            the resource or are over the rate limit.');
            break;
        case 400:
            // fetch the specific XML error
            die('The call to Musoni Services failed and returned 400 error -Bad request-. The parameters passed to the service did not match
                as expected.The exact error is returned in the XML response.');
                break;
            default:
                die('The call to Musoni Services failed and returned an unexpected HTTP status of:' .        $status_code[0]);
    }
    return $ok_code;
}

}
