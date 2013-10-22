<?php
class ConnectionPaypalController extends ConnectionsAppController {
    public $uses = array('Connections.ConnectionPaypal');
    public $components = array('Cookie');
    
    public function requestPermissions() {
        //$this->ConnectionPaypal->requestPermissions('/tasks/tasks/my/');
        $success = $this->ConnectionPaypal->requestPermissions($this->referer());
        if ($success) {
            $this->Cookie->write('ZuhaConnectionsPaypalToken', $success->token, false, 900);
            if (ConnectionManager::getDataSource('paypal')->config['environment'] == 'sandbox') {
                $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_grant-permission&request_token='.$success->token);
            } else {
                $this->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_grant-permission&request_token='.$success->token);
            }
        }
    }
    
    public function createAccount() {
        if ($this->data) {
            $success = $this->ConnectionPaypal->createAccount($this->data, $this->referer());
            if ($success) {
                $this->redirect($success->redirectUrl);
            } else {
                $this->Session->setFlash('Please ensure your information is correct.');
            }
        }
    }
    
    /**
     * Cleverly disguised Chained Payment call
     * @todo ...could possibly be just Pay.. and add Chained Payment receipients dynamically...
     */
    public function cpay() {
		$amount = $this->request->data['Task']['amount'];

		if (is_numeric($amount)) {
		    
		    $amount = number_format ($amount, 2);
		    
		    App::uses('User', 'Users.Model');
            $User = new User;
		    $payeeInfo = $User->findById($this->request->data['Task']['assignee_id']);
		    
		    $success = $this->ConnectionPaypal->doChainedPayment(array(
				'amount' => $amount,
				'secondaryAccount' => $payeeInfo['User']['email'],
				'returnUrl' => $this->referer(),
				'cancelUrl' => $this->referer()
		    ));
	
		    if ($success) {
				if (ConnectionManager::getDataSource('paypal')->config['environment'] == 'sandbox') {
				    $this->redirect('https://www.sandbox.paypal.com/webscr?cmd=_ap-payment&paykey='.$success->payKey);
				} else {
				    $this->redirect('https://www.paypal.com/webscr?cmd=_ap-payment&paykey='.$success->payKey);
				}
		    } else {
				debug($success);
				break;
		    }
		    
		}
		
		$this->Session->setFlash('There was an error.');
		$this->redirect($this->referer());
    }
    
}
