<?PHP

/**
 * Paypal Datasource
 *
 * @author Joel Byrnes <joel@razorit.com>
 * 
 * A list of permissions for $apiPermissionScope are at <https://cms.paypal.com/mx/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_PermissionsGetPermissionsAPI>
 *
 */

App::uses('HttpSocket', 'Network/Http');

class ConnectionsPaypalSource extends DataSource {

    public $description = 'Paypal API';
    //public $useDbConfig = 'paypal';
    private $_httpSocket = null;
    public $config = array(
        'environment' => 'sandbox',
        'sandboxEmailAddress' => '',
        'apiUsername' => '',
        'apiPassword' => '',
        'apiSignature' => '',
        'apiAppId' => 'APP-80W284485P519543T',
        'apiPermissionScope' => array(),
        'chainedPrimaryPercentage' => 0
    );

    public function __construct($config = array()) {
        parent::__construct($config);
        if(defined('__CONNECTIONS_PAYPAL')) {
            $settings = unserialize(__CONNECTIONS_PAYPAL);
	}
        $this->config = array_merge($this->config, $config, $settings);
              
        if($this->config['environment'] == 'sandbox') {
            $this->config['endpoint'] = 'https://svcs.sandbox.paypal.com';
            $this->config['nvpEndpoint'] = 'https://api-3t.sandbox.paypal.com/nvp';
        } else {
            $this->config['endpoint'] = 'https://svcs.paypal.com';
            $this->config['nvpEndpoint'] = 'https://api-3t.sandbox.paypal.com/nvp';
        }
        $this->_httpSocket = new HttpSocket();
        //debug($this->config);
    }

    
    public function query($method, $params, $Model) {
        // you may customize this to your needs.
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
    }

    
    
    /**
     * https://www.x.com/adaptive-accounts-5
     * This is our base Permissions API request sender.
     * @param string $apiOperation
     * @param type $body
     * @return array
     */
    public function sendRequest($apiOperation, $body) {

        if($this->config['environment'] == 'sandbox') {
                $body['sandboxEmailAddress'] = $this->config['sandboxEmailAddress'];
        }        
        
        $body['requestEnvelope']['errorLanguage'] = 'en_US';
        $body['requestEnvelope']['detailLevel'] = 'ReturnAll'; // 0
        #ksort($query);

        $request = array(
            'method' => 'POST',
            'uri' => $this->config['endpoint'].$apiOperation,
            'header' => array(
                'X-PAYPAL-SECURITY-USERID' => $this->config['apiUsername'],
                'X-PAYPAL-SECURITY-PASSWORD' => $this->config['apiPassword'],
                'X-PAYPAL-SECURITY-SIGNATURE' => $this->config['apiSignature'],
                'X-PAYPAL-APPLICATION-ID' => $this->config['apiAppId'],
                'X-PAYPAL-DEVICE-IPADDRESS' => $_SERVER['REMOTE_ADDR'],
                'X-PAYPAL-REQUEST-DATA-FORMAT' => 'JSON',
                'X-PAYPAL-RESPONSE-DATA-FORMAT' => 'JSON',
            ),
            'body' => json_encode($body)
        );

        if($this->config['environment'] == 'sandbox') {
                $request['header']['X-PAYPAL-SANDBOX-EMAIL-ADDRESS'] = $this->config['sandboxEmailAddress'];
        }
        
        $results = $this->_httpSocket->request($request);

	return $this->_ackHandler(json_decode($results));
    }
    
    
    /**
     * This is our base NVP request sender.
     * @param string $apiOperation
     * @param type $query
     * @return array
     */
    public function sendNvpRequest($method, $query) {

        $query['METHOD'] = $method;
        //$query['VERSION'] = '51.0';
        $query['USER'] = $this->config['apiUsername'];
        $query['PWD'] = $this->config['apiPassword'];
        $query['SIGNATURE'] = $this->config['apiSignature'];

        $results = $this->_httpSocket->post($this->config['nvpEndpoint'], $query);
	return $this->_ackHandler(json_decode($results));
    }

    /**
     * A Pass/Fail response parser with error/warning logging capability
     * @param array $result
     * @return boolean
     */
    private function _ackHandler($result) {
        $ackCodes = array(
            'Success' => array(
                'okay' => true,
                'log' => false
            ),
            'Failure' => array(
                'okay' => false,
                'log' => true
            ),
            'Warning' => array(
                'okay' => false,//???
                'log' => true
            ),
            'SuccessWithWarning' => array(
                'okay' => true,
                'log' => true
            ),
            'FailureWithWarning' => array(
                'okay' => false,
                'log' => true
            ),
        );

        if($ackCodes[$result->responseEnvelope->ack]['log']) {
            // log the stuff so that you can contact PayPal Developer Tech Support
            if($weAreLoggingStuff)
                error_log('PayPal:'.$result->responseEnvelope->build.':'.$result->responseEnvelope->correlationId.':'.$result->responseEnvelope->timestamp);
        }

        if($ackCodes[$result->responseEnvelope->ack]['okay']) {
            return $result;
        } else {
            //throw new Exception($result->error[0]->message);
            debug($result);
            return FALSE;
        }

    }
    
}