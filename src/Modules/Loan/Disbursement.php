<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 6/1/18
 * Time: 5:42 PM
 */

namespace Rndwiga\Mifos\Modules\Loan;


use Rndwiga\Mifos\Helpers\MifosUtility;
use Rndwiga\Mifos\MifosXConnection;

class Disbursement extends MifosXConnection
{

    /**
     * @param bool $getArray
     * @param null $loanId
     * @return bool|mixed
    {
    "officeId": 1,
    "clientId": 2,
    "loanId": 23,
    "resourceId": 23,
    "changes": {
        "status": {
                "id": 200,
                "code": "loanStatusType.approved",
                "value": "Approved",
                "pendingApproval": false,
                "waitingForDisbursal": true,
                "active": false,
                "closedObligationsMet": false,
                "closedWrittenOff": false,
                "closedRescheduled": false,
                "closed": false,
                "overpaid": false
        },
        "locale": "en_GB",
        "dateFormat": "dd MMMM yyyy",
        "approvedOnDate": "1 June 2018",
        "expectedDisbursementDate": {},
        "note": "Loan approved via api"
        }
    }
     */
    public function approveLoan($getArray = false, $loanId = null){

        if (is_null($loanId)){
            MifosUtility::logInfo("Loan Id Missing",'loan_approved','loanApproved');
            return false;
        }
        $urlSegment = "/loans/". $loanId ."?command=approve";

        $data = [
            "dateFormat"=> "dd MMMM yyyy",
            "locale"=> "en_GB",
            "approvedOnDate"=> date("d M Y"),
            "expectedDisbursementDate"=> date("d M Y"),
            "note" => "Loan approved via api"
        ];

        $requestedData = $this->curlMakeRequest($urlSegment,json_encode($data));

        MifosUtility::logInfo($requestedData,'loan_approved','loanApproved');

        if ($getArray == true){
            $response =	json_decode($requestedData,true);
        }else{
            $response =	json_decode($requestedData);
        }

        return $response;
    }

    /**
     * @param bool $getArray
     * @param null $loanId
     * @param int $paymentTypeId
     * @return bool|mixed
    {
    "officeId": 1,
    "clientId": 2,
    "loanId": 23,
    "resourceId": 23,
    "changes": {
        "status": {
            "id": 300,
            "code": "loanStatusType.active",
            "value": "Active",
            "pendingApproval": false,
            "waitingForDisbursal": false,
            "active": true,
            "closedObligationsMet": false,
            "closedWrittenOff": false,
            "closedRescheduled": false,
            "closed": false,
            "overpaid": false
        },
        "locale": "en_GB",
        "dateFormat": "dd MMMM yyyy",
        "actualDisbursementDate": "1 June 2018"
    }
    }
     */
    public function disburseLoan($getArray = false, $loanId = null, $paymentTypeId= 3){
        if (is_null($loanId)){
            MifosUtility::logInfo("Loan Id Missing",'loan_disbursed','loanDisbursed');
            return false;
        }
        if (is_null($paymentTypeId)){
            MifosUtility::logInfo("Payment type Missing",'loan_disbursed','loanDisbursed');
            return false;
        }

        $urlSegment = "/loans/". $loanId ."?command=disburse";

        $data = [
            "dateFormat"=> "dd MMMM yyyy",
            "locale"=> "en_GB",
            "actualDisbursementDate"=> date("d M Y"),
            "paymentTypeId"=> (string)$paymentTypeId,
            "note" => "Loan approved via api"
        ];

        $requestedData = $this->curlMakeRequest($urlSegment,json_encode($data));

        MifosUtility::logInfo($requestedData,'loan_approved','loanApproved');

        if ($getArray == true){
            $response =	json_decode($requestedData,true);
        }else{
            $response =	json_decode($requestedData);
        }

        return $response;
    }

    public function approveAndDisburseLoan($getArray = false, $loanId = null, $paymentTypeId= 3){
        $approvedLoan = $this->approveLoan(false,$loanId);

        if ($approvedLoan->changes->status->code == "loanStatusType.approved"){
            $disbursedLoan = $this->disburseLoan(false,$loanId,3);

            if ($disbursedLoan->changes->status->code == "loanStatusType.active"){

                return $disbursedLoan;

            }else{
                MifosUtility::logInfo($disbursedLoan,'failed_loan_disbursement','loanApprovedDisbursed');
                return false;
            }
        }else{
            MifosUtility::logInfo($approvedLoan,'failed_loan_approved','loanApprovedDisbursed');
            return false;
        }
    }
}