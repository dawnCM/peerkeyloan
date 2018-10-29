<?php
/**
 * keyStone(SD) - Site Development
 *
 * Licensed under GNU General Public License v.2
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     TBD
 * @link          TBD
 * @package       app.Controller.ApplicationController
 * @since         keyStone(SD) v1.0 
 * @license       TBD
 */

App::uses('HttpSocket', 'Network/Http');
App::uses('CakeEmail', 'Network/Email');
class ApplicationController extends AppController {
	var $errors = array();
	var $apptype_prefix = ''; 	
	
	public function beforeFilter() {
		parent::beforeFilter();
		//We should have a RequestId by now, if not build one
		if(!$this->Session->check('Application.RequestId')){
			$offer_id 		= $this->Session->read('Application.OfferId');
			$campaign_id 	= $this->Session->read('Application.CampaignId');
			$affiliate_id 	= $this->Session->read('Application.AffiliateId');
			$creative_id	= $this->Session->read('Application.CreativeId');
			$sub_id 		= $this->Session->read('Application.SubId1');
	
		}
	
		$this->Session->write('Application.Url', 'https://'.$_SERVER['SERVER_NAME']);
		$this->Session->write('Application.IPAddress', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$this->Session->write('Application.Template', $this->template);
		$this->Session->write('Application.Mobile', ((Configure::read('Global.Mobile') === null) ? 'false' : 'true'));
		$this->Session->write('Application.Browser', $_SERVER['HTTP_USER_AGENT']);
		$this->Session->write('Application.Campaign_id', '5a0a8fd2de6aa');
		$this->Session->write('Application.Campaign_key', 'tHzMq7JFgdk48BQPfTKR');

		$this->set('loadApplicationJS',true);
	}
		
	/**
	 * Display the application form
	 */
	public function index() {
		$this->layout = 'default';
		$this->Application->set($this->request->data);
		$track_data = array();
		$lead_data = array();
		
		$this->set('captcha', $this->VisualCaptcha->showCaptcha());
		
		//---1-6-2018 for longerterm popup open in Backgroud
		//Validate page 1
		/*if(!$this->Application->validates(array('fieldList' => array('LoanPurpose','CreditRating','Zip','Military','Agree')))) {
			$this->redirect('/');
		}*/

		//Add each variable from page 1 to the session
		foreach($this->request->data AS $key=>$value){
			$this->setSessionData($key, $value);
		}
		
		//Do we have a trackid, if not create one
		if(!$this->Session->check('Application.TrackId')){
			$first_time = true;	
			$track_data['offer_id'] 	= $this->Session->read('Application.OfferId');
			$track_data['campaign_id'] 	= $this->Session->read('Application.CampaignId');
			$track_data['affiliate_id'] = $this->Session->read('Application.AffiliateId');
			$track_data['request_id']	= $this->Session->read('Application.RequestId');
	
			$track_id = rand(10,10000);	
			$this->Session->write('Application.TrackId', $track_id);
			$lead_data['TrackId'] =$track_id;
		}

		//Add Page 1 to track lead
		$lead_data['CallType'] 	= 'internal';
		$lead_data['Template'] 	= $this->template;
		$lead_data['Theme'] 	= $this->theme;
		$lead_data['Mobile'] 	= (Configure::read('Global.Mobile') === null) ? 'false' : 'true';
		$lead_data['sub_id'] 	= $this->Session->read('Application.SubId1');
		$lead_data['sub_id2'] 	= $this->Session->read('Application.SubId2');
		$lead_data['IPAddress'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		//Add post data to lead track array
		foreach($this->request->data AS $key=>$value){
			$lead_data[$key] = $value;
		}
		
		//Populate State Field if Prepop
		if($this->Session->read('Application.Prepop') == 'true' || $this->Session->read('Application.Zip')){
			$httpState = new HttpSocket();
			$result = $httpState->get('https://service.leadstudio.com/getCityStatebyZip/'.$this->Session->read('Application.Zip'));
			preg_match("/callback\(([^()]+)\)/", $result->body, $matches);
		
			if(isset($matches[1])){
				$stateData = json_decode($matches[1], true);
				if(is_array($stateData)){
					if($stateData['status'] == 'success'){
						$lead_data['State'] = $stateData['data']['StateZip']['state'];
						$this->Session->write('Application.State', $stateData['data']['StateZip']['state']);
						$lead_data['City'] = $stateData['data']['StateZip']['city'];
						$this->Session->write('Application.City', $stateData['data']['StateZip']['city']);
					}
				}
			}
		}
		
				
		//Set conditional items based on CreditRating/Military
		if($this->Session->read('Application.CreditRating') == 'excellent' || $this->Session->read('Application.CreditRating') == 'good' || $this->Session->read('Application.CreditRating') == 'fair' || $this->Session->read('Application.CreditRating') == 'unsure' || $this->Session->read('Application.Military') == 'true'){
			//PL Loan Amount Drop Down
			$this->set('LoanAmount', array(
					'1100'=>'$500 - $1000', '1500'=>'$1,000 - $1,999', 
					'2500'=>'$2,000 - $2,999', '3500'=>'$3,000 - $3,999', '4500'=>'$4,000 - $4,999', 
					'5500'=>'$5,000 - $5,999', '6500'=>'$6,000 - $6,999', '7500'=>'$7,000 - $7,999',
					'8500'=>'$8,000 - $8,999', '9500'=>'$9,000 - $9,999', '10000'=>'$10,000 - $10,999',
					'11500'=>'$11,000 - $11,999', '12500'=>'$12,000 - $12,999', '13500'=>'$13,000 - $13,999',
					'14500'=>'$14,000 - $14,999', '15500'=>'$15,000 - $15,999', '16500'=>'$16,000 - $16,999',
					'17500'=>'$17,000 - $17,999', '18500'=>'$18,000 - $18,999', '19500'=>'$19,000 - $19,999',
					'20500'=>'$20,000 - $20,999', '21500'=>'$21,000 - $21,999', '22500'=>'$22,000 - $22,999',
					'23500'=>'$23,000 - $23,999', '24500'=>'$24,000 - $25,000'));
			
			//PL Employee Type Drop Down
			
			//PL
			$this->Session->write('Application.AppType', $this->apptype_prefix.'personalloan');
			
		}else{
			//PD Loan Amount Drop Down
			$this->set('LoanAmount', array(
					'200'=>'$200', '300'=>'$300', '400'=>'$400', '500'=>'$500', '600'=>'$600', '700'=>'$700',
					'750'=>'$750', '800'=>'$800', '900'=>'$900', '1000'=>'$1,000', '1001'=>'Get Me As Much As You Can'));
				
			//PD Employee Type Drop Down

				
			//PD 
			$this->Session->write('Application.AppType', $this->apptype_prefix.'payday');
		}

		$this->set('LoanAmountPayday', array(
					'200'=>'$200', '300'=>'$300', '400'=>'$400', '500'=>'$500', '600'=>'$600', '700'=>'$700',
					'750'=>'$750', '800'=>'$800', '900'=>'$900', '1000'=>'$1,000', '1001'=>'Get Me As Much As You Can'));

		$this->set('EmployeeType', array(
					'self_employed'=>'Self Employed', 'employed'=>'Employed', 'pension'=>'Retired', 'pension'=>'Disabled', 
					'unemployed'=>'Unemployed with income', 'unemployed'=>'Unemployed without income'));
		//update AppType
	
		$this->Session->write('Application.Step',1);
			$response = $this->leadSubmit();


	}
	private function leadSubmit(){

		$lead_data_json = json_encode($this->Session->read('Application'));
		//print_r($lead_data_json);
		//send first step to keystone-stored first step into database
		//$url = "https://api.leadstudio.com/processCrmLeadInactive";
		$url = "https://api.leadstudio.com/processCrmLead";
		$config = array('header'=>array('X-Api-Id'=>Configure::read('Ajax.Id'),'X-Api-Key'=>Configure::read('Ajax.Key'),'Content-Type'=>'application/json'));

		$socket = new HttpSocket(array('timeout'=>180));
		$response = $socket->post($url,$lead_data_json,$config);
		$status_json = json_decode($response);
		
		if(isset($status_json)){
			
			$status = $status_json->status; 
			$redirect = $status_json->redirect;
			
			$response_array['status'] = $status;
			$response_array['redirect'] = $redirect;
			if(isset($status_json->lastInsertId)){

				$this->Session->write('Application.lastInsertId',$status_json->lastInsertId);
			}
			if(isset($status_json->lead_id)){

				$this->Session->write('Application.LeadID',$status_json->lead_id);
			}

		}

		if($this->Session->check('Application.LeadID')){

			$this->Session->write('Application.lead_id',$this->Session->read('Application.LeadID'));
		}
		 	
		return json_encode($response_array);

	}
	/**
	 * Receives an ajax request to save user input to tracklead.
	 */
	public function applicationStep(){
		if($this->request->is('ajax')){
			foreach($this->request->data as $key=>$value){
				$lead_data[$key]=$value;
			}
	
		}
	}

	/**
	 * 
	 * 
	 */
	public function processLead(){

		$this->layout = null;
		$this->autoRender = false;
		$this->response->type('json');
		if($this->request->is('ajax')){
			$response_array = array();
			$this->request->data['AppType'] = $this->Session->read('Application.AppType');
			
			//Grabs the post and cleans out characters in phone fields.  Returns clean value back into POST
			$this->cleanFormPostData();
			
			$this->Application->set(array_merge($this->Session->read('Application'),$this->request->data));
			$this->Application->addDependencies();
			//print_r($this->request->data);
			//print_r($this->Application->data['Application']);

			if($this->Application->validates(array('fieldList' => array('BankAccountType','BankRoutingNumber','BankAccountNumber','BankName','BankTime')))) {
				

				$s_data = array_merge($this->Application->data['Application'], $this->request->data);
				$lead_data_json = json_encode($s_data);
				
				//$url = "https://api.leadstudio.com/processCrmLeadInactive";
				$url = "https://api.leadstudio.com/processCrmLead";
				$config = array('header'=>array('X-Api-Id'=>Configure::read('Ajax.Id'),'X-Api-Key'=>Configure::read('Ajax.Key'),'Content-Type'=>'application/json'));

			    $socket = new HttpSocket(array('timeout'=>180));
			    $response = $socket->post($url,$lead_data_json,$config);
				$status_json = json_decode($response);
				$status = $status_json->status; 
				$redirect = $status_json->redirect;
				$total_sold = $status_json->total_sold;
				$response_array['status'] =$status;
				$response_array['redirect'] = $redirect;
				$response_array['total_sold'] = $total_sold;

				if($total_sold == 0){
					
					if($this->Session->read('Application.LoanAmount1')==""){
						
						$this->Session->write('Application.LoanAmount1', $s_data['LoanAmount']);
					}

				//$this->Session->write('Application.loan_counter',$this->Session->read('Application.loan_counter')+1);
				}
				
				/*if($this->Session->read('Application.loan_counter') >= 3){

					$response_array['status'] ="Thankyou";
				}*/

				$response_array['AppType'] = $this->Session->read('Application.AppType');
				
				return json_encode($response_array);

			}else{

				$response_array['status'] = 'error';
				$response_array['redirect'] = '';
				$validation_array = $this->Application->flatErrorArray();
				
				$track_id = $this->Session->read('Application.TrackId');
				$json = json_encode(array('ERRORS' => array(501=>'Failed Validation - Application Controller')));
				
				if(!empty($validation_array))$this->errors = array_merge($this->errors, $validation_array);
				return json_encode($response_array);
			}
		}
	}

	public function fault(){
		$this->layout = 'default';
		$creditRating = $this->Session->read('Application.CreditRating');
		$offerLink = "http://www.yahoo.com";
		$this->Session->destroy();
		$this->set('offerLink', $offerLink);
	}

	public function thankyou(){

		$this->layout = 'default';
		$this->Session->destroy();
	}
	
	/**
	 * Set application variables in the user session
	 * @param string $key
	 * @param mixed $value
	 */
	private function setSessionData($key, $value){
		$this->Session->write('Application.'.$key, $value);
	}
	
	/**
	 * Set application variables in the user session via ajax
	 */
	public function setSessionDataAjax(){
		$this->layout = null;
		$this->autoRender = false;
		$this->response->type('json');
		if($this->request->is('ajax')){
			
			//Clean Data
			$this->cleanFormPostData();
			

			//$this->Session->write('Application.LoanAmountOld',$this->Session->read('Application.LoanAmount'));				
			//if($this->Session->read('Application.Step') == 7){
				
				if($this->Session->check('Application.LoanAmount1') && !$this->Session->check('Application.LoanAmount2'))
				{
					$this->Session->write('Application.LoanAmount2', $this->request->data['LoanAmount']);
					$this->Session->write('Application.LoanAmount', $this->request->data['LoanAmount']);
				}

				if($this->Session->check('Application.LoanAmount1') && $this->Session->check('Application.LoanAmount2'))
				{
					$this->Session->write('Application.LoanAmount', $this->request->data['LoanAmount']);
				}
			//}

			if($this->Session->read('Application.Step') != 7){
				foreach($this->request->data as $key=>$value){
					$this->Session->write('Application.'.$key, $value);
				}
			}

			//.print_r($this->Session->read('Application'));
			if($this->Session->read('Application.Step') != 1 && $this->Session->read('Application.Step') != 6){

					$response = $this->leadSubmit();
					$status_json = json_decode($response);
				    $status = $status_json->status; 
					$redirect = $status_json->redirect;
					$response_array['status'] = $status;
					$response_array['redirect'] = $redirect;

			}
		}
	}
	
	
	//Session data is cleaned.  Now we have to make sure the form data is clean before validation
	private function cleanFormPostData(){
		$filter_array = array('SecondaryPhone','Ssn','CoSsn','WorkPhone','CoPrimaryPhone','CoWorkPhone','PrimaryPhone');
		foreach($this->request->data as $key=>$value){
				
			//take out the characters and spaces					
			if(in_array($key,$filter_array)){
				$this->request->data[$key] = str_replace(array('-','(',')',' '), array('','','',''), $value);	
			}

		}	
	}

	
	public function validateCaptcha(){
		$this->layout = null;
		$this->autoRender = false;
		$this->response->type('json');
		if($this->request->is('ajax')){
			$response_array = array();
	
			$hash_request = md5($this->request->data['request']).'c';
				
			if($hash_request == $this->request->data['chosen']){
				$response_array['status'] = 'success';
			}else{
				$response_array['status'] = 'error';
			}
			return json_encode($response_array);
		}
	}
	
	public function regenerateCaptcha(){
		$this->layout = null;
		$this->autoRender = false;
		$this->response->type('json');
		if($this->request->is('ajax')){
			$response_array = array('status'=>'success');
			$response_array['data']=$this->VisualCaptcha->showCaptcha();
				
			return json_encode($response_array);
		}
	}
	
	public function sendValidationCode(){
		$this->layout = null;
		$this->autoRender = false;
		if($this->request->is('ajax')){
			
			$code = rand(1000,99999);
			$this->Session->write('validation', $code);
			$sendemail = $this->request->data['email'];
			$first_name = $this->request->data['firstname'];
			$last_name = $this->request->data['lastname'];
		
			$Email = new CakeEmail();
			$Email->viewVars(array('code'=>$code,'firstname'=>$first_name,'lastname'=>$last_name,'title'=>'Validation Code: '.$code));
			$Email->template('validation', 'simplesquare')
				->emailFormat('both')
				->to(array($sendemail))
				->from(array('validation@peerkeyloan.com'))
				->subject('Peer Key Validation '.$code)
				->send();
		}
	}
		
	public function verifyValidationCode($code){
		$this->layout = null;
		$this->autoRender = false;
		$this->response->type('json');
		if($this->request->is('ajax')){
			$original = $this->Session->read('validation');
			if($original == $code){
				$response_array['data'] = true;
			}else{
				$response_array['data'] = false;
			}
			return json_encode($response_array);
		}
	}
}
