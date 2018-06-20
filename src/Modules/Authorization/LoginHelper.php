<?php
/**
 * Created by PhpStorm.
 * User: rndwiga
 * Date: 3/28/18
 * Time: 12:19 PM
 */

namespace Rndwiga\Mifos\Api;


use Rndwiga\Mifos\MifosXConnection;

class LoginHelper extends MifosXConnection
{
    public function loginToTenant($username, $password){
        $urlSegment = "/authentication?username=" . $username . "&password=" . $password;
        return json_decode($this->curlLoginRequest($urlSegment));
    }

    public function getUserApiKey($username, $password){
        $apiUser = $this->loginToTenant($username, $password);
        return $apiUser->base64EncodedAuthenticationKey;
    }

    public function getUserId($username, $password){
        $apiUser = $this->loginToTenant($username, $password);
        return $apiUser->userId;
    }

    public function getUserOfficeDetails($username, $password){
        $apiUser = $this->loginToTenant($username, $password);
        return [
            'officeId' => $apiUser->officeId,
            'officeName' => $apiUser->officeName,
        ];
    }

    public function getUserRoles($username, $password){
        $apiUser = $this->loginToTenant($username, $password);
        return $apiUser->roles;
    }

    public function getUserPermissions($username, $password){
        $apiUser = $this->loginToTenant($username, $password);
        return $apiUser->permissions;
    }
}