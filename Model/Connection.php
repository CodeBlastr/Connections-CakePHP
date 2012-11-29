<?php
App::uses('ConnectionsAppModel', 'Connections.Model');
App::import('Vendor', 'OAuth/OAuthClient');

class Connection extends ConnectionsAppModel {

	public $name = 'Connection';
	
	public $googleClientId = ''; // get from google apis console https://code.google.com/apis/console/
	public $googleClientAPIKey = ''; // get from google apis console https://code.google.com/apis/console/
	public $googleClientSecret = ''; // get from google apis console https://code.google.com/apis/console/
	public $googleRedirectUri = ''; // must exist character for character at google apis console 
	
	public $twitterClientAPIKey = '';
	public $twitterClientSecret = '';
	
	public $Client = '';
	
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'dependent' => false,
		)
	);
	
	public function __construct($id = false, $table = null, $ds = null) {
		if (defined('__USERS_CONNECT_APPS')) {
			extract(__USERS_CONNECT_APPS);
		}
		
    	parent::__construct($id, $table, $ds);
	}
	
	
	/**
	 * This function runs after successful login, but before the redirection.
	 * We may need to redirect them to a more important page than whatever the app was going to show them.
	 * 
	 * @param integer $userId
	 */
	public function afterLogin($userId) {
		// get all of their connections
		$connections = $this->find('all', array('conditions' => array('user_id' => $userId)));
		
		// run the afterLogin callback on all of them
		foreach($connections as $connection) {
			//App::i
			//$Class = new $connection;
		}
	}
	
	
	public function google($accessCode) {
		
		$connectType = 'google'; // used to match in case there is an update (don't change, unless you want all new records saved)
		$this->Client = $this->createClient($connectType);
		
		// get tokens, (all fields required)			
		$params['code'] = $accessCode; // one time use authorization_id from google
		$params['client_secret'] = $this->googleClientSecret;
		$params['grant_type'] = 'authorization_code';
		$params['redirect_uri'] = $this->googleRedirectUri;
		$params['client_id'] = $this->googleClientId;
					
		// post($accessTokenKey, $accessTokenSecret, $url, array $postData = array()) 
		$response = $this->Client->post($params['code'], $params['client_secret'], 'https://accounts.google.com/o/oauth2/token', $params);
		$response = json_decode($response->body, true); // parse the response into an array
		
		if (!empty($response['access_token']) && $this->googleVerifyToken($response['access_token'])) {		
			// add the access token to the session (expires eventually)
			CakeSession::write('Google.accessToken', $response['access_token']);
			
			// add the refresh token to the database in order to get a new access token without further user verification (CODING NOTE : refresh_token is only available if manually verified, so &approval_prompt=force&access_type=offline is required in the access request)
			$data['Connection']['type'] = $connectType;
			$data['Connection']['value'] = serialize($response);
			try {
				$this->add($data);
				return true;
			} catch (Exception $e) {
				throw new Exception ($e->getMessage());
			}				
		} else {
			throw new Exception (__('Invalid Grant')); // left vague on purpose (security)
		}
	}
	

/**
 * Create a client method
 */
	private function createClient($type = 'google') {
		if ($type == 'google') {
			return new OAuthClient($this->googleClientAPIKey, $this->googleClientSecret); // google app
		} else if ($type == 'twitter') {
    		return new OAuthClient($this->twitterClientAPIKey, $this->twitterClientSecret); // twitter app	
		}
	}
	
	
	
	
/** 
 * Uses session vars from a 3rd party authentication to create a relationship
 * between a user id here, and a user id on the 3rd party site.
 * 
 * @return bool
 */
	public function add($data) {
		$data['Connection']['user_id'] = CakeSession::read('Auth.User.id');
		$data = $this->_cleanData($data);
		
		if ($this->save($data)) {
			return true;
		} else {
			throw new Exception(__('Connection to app failed'));
		}
	}
	
/**
 * Clean data
 *
 * @return array
 */
	protected function _cleanData($data) {
		
		// check for an existing user_id and matching type and update the id if so
		$oldConnection = $this->find('first', array(
			'conditions' => array(
				'Connection.user_id' => $data['Connection']['user_id'], 
				'Connection.type' => $data['Connection']['type'],
				),
			));
		
		if (!empty($oldConnection)) {
			$data['Connection']['id'] = $oldConnection['Connection']['id'];
		}
		
		return $data;
	}
	
	
	

/**
 * Google verification
 * 
 * An extra verification recommended by Google to thwart the Confused Deputy Problem
 * @return bool
 * @todo move this to a data source
 */
	public function googleVerifyToken($accessToken) {				
		$verify['access_token'] = $accessToken; // not sure that its needed twice like this, but what does it hurt?
		$verification = $this->Client->post($accessToken, $this->googleClientSecret, 'https://www.googleapis.com/oauth2/v1/tokeninfo', $verify);
		$verification = json_decode($verification->body, true); // parse the response into an array
		
		if ($verification['audience'] == $this->googleClientId) {
			return true;
		} else {
			return false;
		}
	}

/**
 * List Google Contacts
 * 
 * @todo move this to a data source
 */
	public function listGoogleContacts($accessToken) {
		
		$connectType = 'google'; // used to match in case there is an update (don't change, unless you want all new records saved)
		$this->Client = $this->createClient($connectType);
		
		//get($accessTokenKey, $accessTokenSecret, $url, array $getData = array())
		$request = array(
			'method' => 'GET',
			'uri' => 'https://www.google.com/m8/feeds/contacts/default/full/?max-results=500',
			'header' => array(
				'Gdata-version' => '3.0',
				'Authorization' => 'OAuth '.$accessToken,
				),
			); 
			
			
		$contacts = $this->Client->request($request);
		$xml = Xml::toArray(Xml::build($contacts->body));
		debug($xml);
		break;
		
		$verification = json_decode($verification->body, true); // parse the response into an array
		
		if ($verification['audience'] == $this->googleClientId) {
			return true;
		} else {
			return false;
		}
	}
	
}