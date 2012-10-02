<?php
/**
 * @author Joel Byrnes <joel@razorit.com>
 */

ConnectionManager::create('paypal', array(
     'datasource' => 'Connections.ConnectionsPaypalSource'
));

App::uses('ConnectionsAppModel', 'Connections.Model');
class ConnectionPaypal extends ConnectionsAppModel {

    public $name = 'ConnectionPaypal';
    public $hasOne = 'Connection';
    public $useTable = false;
    public $useDbConfig = 'paypal';


    /**
     * 
     * @param type $userId
     * @return boolean
     */
    public function checkAccountLinkage($userId = null) {
        
        if(!$userId) $userId = CakeSession::read('Auth.User.id');
        
        $usersPaypalData = $this->Connection->find(array(
            'conditions' => array(
                'user_id' => $userId,
                'type' => 'paypal'
                )
            ));
        
        $usersPaypalData = unserialize($usersPaypalData);

        if($usersPaypalData['paypal_id']) {
            return $usersPaypalData['paypal_id'];
        } else {
            return FALSE;
        }
    }
    

    /**
     * 
     * @param string $returnUrl
     * @return boolean
     */
    public function requestPermissions($returnUrl) {
        $query = array(
            'scope' => ConnectionManager::getDataSource('paypal')->config['apiPermissionScope'],
            //'callback' => 'http://' . $_SERVER['SERVER_NAME'] . $returnUrl
            'callback' => $returnUrl
        );
        
        if(empty($query['scope']))            return FALSE;

        $result = $this->sendRequest('/Permissions/RequestPermissions', $query);
        if($result) {
            return $result;
        } else {
            return FALSE;
        }
    }

    /**
     * Runs after a user has given us permission to link their account.
     * @param type $token
     * @param type $verificationCode
     * @return boolean
     */
    public function getAccessToken($token, $verificationCode) {

        $query = array(
            'token' => $token,
            'verifier' => $verificationCode
        );

        $result = $this->sendRequest('/Permissions/GetAccessToken', $query);
        if($result) {
            
            $value = serialize(array(
                'token' => $result['token'],
                'tokenSecret' => $result['tokenSecret']
            ));
            
            $this->Connection->save(array(
                'user_id' => CakeSession::read('Auth.User.id'),
                'type' => 'paypal',
                'value' => $value
            ));
            
            return TRUE;
            
        } else {
            return FALSE;
        }

    }

    
    /**
     * @todo Write a function to get the user's data and save that as well.
     * This will be done immediately after getAccessToken()
     * 
     * https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsUsing
     * 
     * Permissions/GetBasicPersonalData 
     * Permissions/GetAdvancedPersonalData  
     */
    
    
    /**
     * 
     * @param type $data
     * @return boolean
     */
    public function getBalance($paypalId) {
        $request = array(
            'SUBJECT' => $paypalId
            );
        
        $result = $this->sendNvpRequest('GetBalance', $request);
        if($result) {

            return $result['L_AMT'];

        } else {
            return FALSE;
        }
        
    }

    
    /**
     * 
     * @param type $data
     * @return boolean
     */
    public function doChainedPayment($data) {
        
	$percentage = ConnectionManager::getDataSource('paypal')->config['chainedPrimaryPercentage'] / 100;
	
        $primaryAmount = number_format(
                $data['amount'] * $percentage,
                2);
        $secondaryAmount = number_format(
                $data['amount'] - $primaryAmount,
                2);

        $request = array(
            'actionType' => 'PAY',
            'receiverList' => array(
                'receiver' => array(
		    array(
			'email' => ConnectionManager::getDataSource('paypal')->config['chainedPrimaryEmail'],
			'amount' => $data['amount'],
			'primary' => 'true'
			),
		    array(
			'email' => $data['secondaryAccount'],
			'amount' => $secondaryAmount
		    ),
                ),
            ),
            'currencyCode' => 'USD',
            'cancelUrl' => $data['cancelUrl'],
            'returnUrl' => $data['returnUrl'],
        );
        
        $result = $this->sendRequest('/AdaptivePayments/Pay', $request);
        if($result) {

            return $result;

        } else {
            return FALSE;
        }
    }
    

    /**
     * 
     * @param array $data
     * @return boolean Returns a URL to confirm the account OR false
     */
    public function createAccount($data, $returnUrl) {
        $request = array(
            'accountType' => 'Personal',
            'name' => array(
                'firstName' => ($data['firstName']),
                'lastName' => ($data['lastName']),
            ),
            'address' => array(
                'line1' => ($data['line1']),
                'city' => ($data['city']),
                'state' => ($data['state']),
                'postalCode' => ($data['postalCode']),
                'countryCode' => 'US',
            ),
            'citizenshipCountryCode' => 'US',
            'contactPhoneNumber' => ($data['contactPhoneNumber']),
            'dateOfBirth' => ($data['dateOfBirth']).'Z',
            'createAccountWebOptions' => array(
                'returnUrl' => $returnUrl, //where the user is redirected after completing the PayPal account setup
            ),
            'currencyCode' => 'USD',
            'emailAddress' => ($data['emailAddress']),
            'preferredLanguageCode' => 'en_US',
            'registrationType' => 'Web'
        );

        if(!empty($data['line2'])) $request['address']['line2'] = ($data['line2']);


        $result = $this->sendRequest('/AdaptiveAccounts/CreateAccount', $request);
        if($result) {

            return $result;

        } else {
            return FALSE;
        }
    }

}