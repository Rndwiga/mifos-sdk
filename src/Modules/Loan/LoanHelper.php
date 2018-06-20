<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 3/24/18
 * Time: 4:50 PM
 */

namespace Rndwiga\Mifos\Modules\Loan;

use Rndwiga\Mifos\DataUpload;
use Rndwiga\Mifos\Helpers\MifosUtility;
use Rndwiga\Mifos\MifosXConnection;


class LoanHelper extends MifosXConnection
{
    public function createLoan($clientId, $loanAmount){

        $data = [
            "dateFormat"=> "dd MMMM yyyy",
            "locale"=> "en_GB",
            "clientId"=> $clientId,
            "productId"=> 1,
            "principal"=> (string)$loanAmount,
            "loanTermFrequency"=> 1,
            "loanTermFrequencyType"=> 2,
            "loanType"=> "individual",
            "numberOfRepayments"=>1,
            "repaymentEvery"=> 1,
            "repaymentFrequencyType"=> 2,
            "interestRatePerPeriod"=> 0,
            "amortizationType"=> 1,
            "interestType"=> 0,
            "interestCalculationPeriodType"=> 1,
            "transactionProcessingStrategyId"=> 1,
            "expectedDisbursementDate"=> date("d M Y"),
            "submittedOnDate"=> date("d M Y")
        ];

        MifosUtility::logInfo(json_encode($data),'mifos_send_data');
        $mifosLoan = (new DataUpload())->sendData($data,"/loans");
        MifosUtility::logInfo(json_encode($mifosLoan),'mifos_response');
        MifosUtility::logInfo(json_encode($mifosLoan),'mifos_response_unwrapped');

        return $mifosLoan;
    }

    public function getLoans($getArray = false,$loanId = null,$withTransactions = true,$withClientDetails = false){


        if(isset($loanId)){
            $urlSegment = "/loans/". $loanId;
        }else{
            $urlSegment = "/loans/";
        }

        if ($withTransactions == true){
            $urlSegment = $urlSegment."?associations=transactions";
        }

        $requestedData = $this->curlGetRequest($urlSegment);

        if ($getArray == true){
            $response =	json_decode($requestedData,true);
        }else{
            $response =	json_decode($requestedData);
        }


        if ($withClientDetails == true){
            $client = new ClientHelper();
           $clientData = $client->getClients($getArray,(json_decode($requestedData))->clientId,false,$withClientDetails,false);

           if ($getArray == true){
               $response['clientDetails'] = $clientData;
           }else{
               $response->clientDetails = $clientData;
           }
        }

        return $response;
    }

    public function getLoanDisbursementChannel($loanId){
        $loan = $this->getLoans(false,$loanId,true);

        return $loan->transactions;
    }

    public function getLoanWithClientDetails(){

    }

    public function getLoanData($urlSegment = "/loans/", $loanId){
        $urlExtention = $urlSegment . $loanId;
        $loan =	$this->curlGetRequest($urlExtention);

        isset($loan['graceOnPrincipalPayment']) ? $gracePrincipal = $loan['graceOnPrincipalPayment'] : $gracePrincipal = null;
        isset($loan['graceOnInterestPayment']) ? $graceInterest = $loan['graceOnInterestPayment'] : $graceInterest = null;
        $loanData = array(
            'submissionDate' => $loan['timeline']['submittedOnDate']['0'] .'/' . $loan['timeline']['submittedOnDate']['1'] .'/' . $loan['timeline']['submittedOnDate']['2'],
            'disbursementDate' => $loan['timeline']['expectedDisbursementDate']['0'] .'/' . $loan['timeline']['expectedDisbursementDate']['1'] .'/' . $loan['timeline']['expectedDisbursementDate']['2'],
            'repaymentDate' => $loan['expectedFirstRepaymentOnDate']['0'] .'/' . $loan['expectedFirstRepaymentOnDate']['1'] .'/' . $loan['expectedFirstRepaymentOnDate']['2'],
            'principalApplied' => $loan['principal'],
            'interestRate' => $loan['interestRatePerPeriod'],
            'repaymentFrequency' => $loan['termPeriodFrequencyType']['value'],
            'repaymentEvery'	=> $loan['repaymentEvery'],
            'installmentsNumber' => $loan['termFrequency'],
            'gracePrincipal' => $gracePrincipal,
            'graceInterest' => $graceInterest
        );

        return (object)$loanData;
    }
}