<?php


App::uses('HttpSocket', 'Network/Http', 'File');
class TestController extends AppController {
	var $errors = array();	


		
	/**
	 * Display the application form
	 */
	public function index() {
	
	 	$theme = 'default';//$this->theme;
		$file = new File('../tmp/json/theme.json', false, 0777); //create object - will not error at this level if missing
		
		if( $file->exists() ){ //Does file exist
			$file_contents = @$file->read(false, 'rb', false);
			if($file->size() > 0 && $jsonToArray = @json_decode($file_contents,true)){ //check if valid json
				if(is_array($jsonToArray)){			
					$theme = $this->themeLogic($jsonToArray);
					if(trim($theme) == false || trim($theme) == ""){
						$theme = $this->theme; //set back to default	
					}
				}
			}
		
		}
		
		
		
		echo $theme;
		
		exit;
	}
	
	
	private function themeLogic(ARRAY $data){
		//json by time - {"c":"24","a":"t","theme":["alpha","beta"],"v":"23:56"}
		//json by percentage -  {"c":"24","a":"s","theme":["alpha","beta"],"v":["50","50"]}
		//json by campaign only - {"c":"24","a":"c","theme":"alpha"}
		
		$specific_config = false; //array holder for a specific campaign config
		$default_config = false; //array holder for default config for all campaigns
		
		$campaignid = 33;//$this->Session->read('Application.CampaignId');
		
		foreach($data as $k=>$v){
			//set default array	if present
			if($v['c'] == "default"){
				$default_config = $v;
				continue;	
			}
			
			//set specific campaign config is present
			if($v['c'] == $campaignid){
				$specific_config = $v;
			}
		}
		
		if($specific_config === false && $default_config === false){ //Use app controller theme
			return false;
		}else if(is_array($specific_config)){ //rank 1
			$config = $specific_config;
		}else if(is_array($default_config)){ // rank 2
			$config = $default_config;
		}else{
			return false; //use app controller theme
		}
		
		
		switch ($config['a']) { //actions - Campaign(c) Split(s)  Time(t)
			case 's': //split percentage
				$campaign_id = $config['c'];
				$theme1 = $config['theme'][0];
				$theme2 = $config['theme'][1];
				$split_percentage1 = (INT) $config['v'][0] / 10; //whole number 1-10
				$split_percentage2 = (INT) $config['v'][1] / 10; //whole number 1-10
				$random_number = rand(1, 10);
				
				return ucfirst((($random_number <= $split_percentage1) ? $theme1 : $theme2 ));
				break;
			
			case 't': //Split by Time
				$campaign_id = $config['c'];
				$theme1 = $config['theme'][0];
				$theme2 = $config['theme'][1];
				$split_unix = strtotime($config['v']); //to unix format HH:MM 24hour format
				$current_unix = strtotime("now");
				return ucfirst((($current_unix < $split_unix) ? $theme1 : $theme2 ));
				break;
			
			case 'c': //Campaign
				$campaign_id = $config['c'];
				$theme = $config['theme'];
				return ucfirst($theme);
				break;
				
			default:
				return false;
				break;
		}
		
	
	}
	
	
}