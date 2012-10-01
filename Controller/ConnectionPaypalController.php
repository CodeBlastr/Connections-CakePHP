<?PHP
/**
 * 
 */
class ConnectionPaypalController extends ConnectionsAppController {
    public $uses = array('Connections.ConnectionPaypal');
    public $components = array('Cookie');
    
    public function requestPermissions() {
        //$this->ConnectionPaypal->requestPermissions('/tasks/tasks/my/');
        $success = $this->ConnectionPaypal->requestPermissions($this->referer());
        if($success) {
            $this->Cookie->write('ZuhaConnectionsPaypalToken', $success->token, false, 900);
            if(ConnectionManager::getDataSource('paypal')->config['environment'] == 'sandbox') {
                $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_grant-permission&request_token='.$success->token);
            } else {
                $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_grant-permission&request_token='.$success->token);
            }
        }
    }
    
    public function createAccount() {
        if($this->data) {
            $success = $this->ConnectionPaypal->createAccount($this->data, $this->referer());
            if($success) {
                $this->redirect($success->redirectUrl);
            } else {
                $this->Session->setFlash('Please ensure your information is correct.');
            }
        }
    }
}