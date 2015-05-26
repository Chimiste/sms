 /**
	 * @package:SMS
	 * @MathsClub::uploadStudents().
	 * @Author:Techno Services
	 */
	public function uploadStudents()
	{
        
		$school_id = $this->uri->segment(2);
		$dir = $this->config->item('abs_upload_paths');
		$rel_dir = $this->config->item('student_csv_dir');
		$dir = $dir['staff_csv_dir'];
	    $config['upload_path'] = $dir;
		$config['allowed_types'] = 'csv|CSV';
		//$config['max_size']	= '100';
		/*$config['max_width']  = '1051';
		$config['max_height']  = '351';
*/
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
        $this->status = array();
		$status = 0;
		$msg ='An error ocured,please try again later.here';
        $ref  = 'upload_staff';
		if ( ! $this->upload->do_upload("upload_student_file"))
		{
		    $error = array('error' => $this->upload->display_errors());  
			
			//print_r($error); 
			$status = 0;
			$msg = $this->upload->display_errors();
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			//print_r($data);
		    $rel_dir.$data['upload_data']['file_name'];
			$status = 0;
			$msg = 'An error occured, try again.';
			$ref = 'upload_student';
			$targetpath  = $rel_dir.$data['upload_data']['file_name'];
			
			   if($targetpath){
                   $n=0;
				   $fileHandle = fopen($targetpath, "r");
				   $records = array();
				   while(!feof($fileHandle)){
					   
					  $lineOfText = fgetcsv($fileHandle);
					   
					  if(!empty($lineOfText[0])){
						  
						 if($n>0){
							
							
							 $password = getToken(8);
							 $hash = hashSSHA($password);
							 $encrypted_password = $hash["encrypted"]; // encrypted password
							 $salt = $hash["salt"]; // salt
							 $this->records = array(
											
												 'firstname' => setFirstLetterCapital($lineOfText[1]),
												 'lastname' => setFirstLetterCapital($lineOfText[2]),
												 'user_email' => $lineOfText[3],
												 'user_pass' => $encrypted_password,
												 'salt' => $salt,
												 'user_activation_key' => getToken(46),
												 'user_priority' => 7,
												 'user_status' => 3,
												 'username' => generateUsername($lineOfText[1].' '.$lineOfText[2])
												// 'user_group_id' => $this->results
												 
												 
							 );
							 
					     $this->where = array('user_email' => $lineOfText[3]);
							 
					     if(!$this->common_model->isRecordsExists($this->common_model->_usersTable,$this->where)){
				 
							 if($this->common_model->insertRecords($this->common_model->_usersTable, $this->records)){
								 
								 $status = 1;
								 $msg = "Save successfully.";
								 
								 			$this->records = array(
										   'school_ID' => $school_id,
										   'student_id' => $password,
										   'student_grade_id' => 0 ,
										   'student_section_id' => 0 ,
										   'student_session' =>  $lineOfText[0],
										   'student_firstname' => setFirstLetterCapital($lineOfText[1]),
										   'student_middlename' => '',
										   'student_nationality' => $lineOfText[4],
										   'student_religion' => $lineOfText[5],
										   'student_national_id' => $lineOfText[6],
										   'national_id_expiration' => $lineOfText[7],
										   'passport_number' => $lineOfText[8],
										   'passport_validity' => $lineOfText[9],
										   'has_sublings' => '',
										   'sublings_order' => '',
										   'language_spoken_at_home' => '',
										   'current_grade_level' => '',
										   'grade_level_applied' => '',
										   'house_number' => $lineOfText[16],
										   'blood_group' => '',
										   'student_shift' => '',
										   'student_status' => 1,
										   'student_lastname' => setFirstLetterCapital($lineOfText[2]),
										   'student_address1' => $lineOfText[17],
										   'student_email' => $lineOfText[3],
										   'student_country' => $lineOfText[19],
										   'student_city' => $lineOfText[18],
										   //'student_phonenumber' => $sms_student_phone_no,
										   'student_dob' => $lineOfText[14],
										   'student_gender' => $lineOfText[10],
										   'student_photo' => '',
										   'student_comment' => '',
										   'student_created' => date("Y-m-d")
						                 );
										 
										 
										 $this->common_model->insertRecords($this->common_model->_studentsTable, $this->records);
										 
										  $data['username'] = $lineOfText[3];
										  $data['password'] = $password;
										  $subject = "Access ".getOptions('site_short'). ' System';    
										  $mail_data = array(
													  'from' => getOptions('site_email'),
													  'from_name' => getOptions('site_short'),
													  'to' => $lineOfText[3],
													  'subject' => $subject,
													  'message' => getStudentAuthNotifyHtml($lineOfText[3], $password),
													  'cc' => '',
													  'bcc' => '',
													  'attach' => ''
										   );
										  
										  alertnativeEmail($mail_data);
										  
										  
						$parent = array(
				          'student_id' => $password,
				          'parent_firstname' => $lineOfText[20],
						  'parent_middlename' => '',
						  'parent_lastname' => $lineOfText[21],
						  'parent_nationality' => $lineOfText[22],
						  'parent_religion' => $lineOfText[23],
						  'parent_national_id' => $lineOfText[24],
						  'parent_national_id_validity' => $lineOfText[25],
						  'parent_dob' => $lineOfText[26] ,
						  'parent_home_phone' => $lineOfText[27],
						  'parent_cell_phone' => $lineOfText[29],
						  'parent_email' => $lineOfText[30],
						  'company_name' => $lineOfText[31],
						  'business_address' => $lineOfText[32],
						  'home_address' => $lineOfText[33],
						  'parent_city' => $lineOfText[34],
						  'parent_country' => $lineOfText[35],
						  'parent_blood_group' => '',
						  'mother_firstname' => $lineOfText[36],
						  'mother_middlename' => '',
						  'mother_lastname' => $lineOfText[37],
						  'mother_cell_phone' => $lineOfText[38],
						  'mother_level_education' => $lineOfText[39],
						  'emergency_contact_name' => $lineOfText[40],
						 // 'emergency_contact_mobile' => $lineOfText[41],
						  
				);
				
				if($lineOfText[30]){
					
					$this->common_model->insertRecords($this->common_model->_parentsTable, $parent);
				
				    $password = getToken(10);
					$hash = hashSSHA($password);
					$encrypted_password = $hash["encrypted"]; // encrypted password
					$salt = $hash["salt"]; // salt
					$this->records = array(
					                 'school_ID' => $school_id,
					                 'firstname' => setFirstLetterCapital($lineOfText[20]),
									 'lastname' => setFirstLetterCapital($lineOfText[21]),
					                 'user_email' => $lineOfText[30],
									 'user_pass' => $encrypted_password,
									 'salt' => $salt,
									 'user_activation_key' => getToken(40),
									 'user_priority' => 10,
									 'user_status' => 3,
									 'username' => generateUsername($lineOfText[20].' 
									 '.$lineOfText[21])
									 
					);
					
					if($this->common_model->insertRecords($this->common_model->_usersTable, $this->records)){
					
					$data['username'] = $lineOfText[30];
					$data['password'] = $password;
					$subject = "Access ".getOptions('site_short'). ' System';    
					$mail_data = array(
								'from' => getOptions('site_email'),
								'from_name' => getOptions('site_short'),
								'to' => $lineOfText[30],
								'subject' => $subject,
								'message' => getParentAuthNotifyHtml($lineOfText[30], $password),
								'cc' => '',
								'bcc' => '',
								'attach' => ''
		             );
					
						//notify the parent of the student
					  // sendingEmail($mail_data);
					   alertnativeEmail($mail_data);
					   
				  $lastid = $this->common_model->getLastInserted();
				  $records = array('school_ID' => $school_id,'user_id' => $lastid);
				  $this->common_model->insertRecords($this->common_model->_schoolGroupTable, $records);	
					}
				    	
				}
				 
				 
								 
							 }
						 }
										 
						 }
						 
					  }
					  
				     $n++;}
				   
			   }
			   
		}
		
		 $this->status['status'] = $status;
		 $this->status['msg'] = $msg;
		 $this->status['ref'] = $ref;
		
		 echo jsonEncode($this->status);
		
	}
