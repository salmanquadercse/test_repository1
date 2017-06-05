<?php
class Api extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->load->model('apimodel');
    }

    /*--------------- check login ------------------*/
    /**
     *
     */
    function check_login()
    {
       // $json = file_get_contents('php://input');
        $json = '{"username":"quader","password":"123456"}';
        $jsondata = json_decode($json, TRUE);

        $data['username'] = $jsondata['username'];
        $data['passwd'] = md5($jsondata['password']);

        header("content_type: application/json", True);
        echo json_encode($data);
    }
	function new_category($title = null,$created_at=null,$image = null){
		if($title == null && $created_at == null){
			$title      = (isset($_REQUEST['title']))?$_REQUEST['title']:'';
			$created_at = (isset($_REQUEST['created_at']))?$_REQUEST['created_at']:'';
			$image = (isset($_REQUEST['image']))?$_REQUEST['image']:'';
		}
		$image_file = md5(strtotime(date("Y-m-d h:i:s")));
		file_put_contents(FCPATH.'img/'.$image_file.'.jpg',base64_decode($image));
		$data['title']      = $title;
		$data['created_at'] = date('Y-m-d',strtotime($created_at));
		$data['photograph'] = $image_file.'.jpg';
		$table = 'post_category';
		if($data['title'] && $data['created_at']){
			$inserted_id = $this->apimodel->insert_new_category($table,$data);
			$response['response'] = array(
				'inserted_id' => $inserted_id,
				'status' => 'Success'
			);
		}else{
			$response['response'] = array(
				'status' => 'Faild'
			);
		}
		header('Content-type:application/json');
		echo json_encode($response);
	}
	function curl_request(){
		// Get cURL resource
		$file = base_url().'img/google.png';
		$encoded_img = base64_encode(file_get_contents($file));
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => false,
			//CURLOPT_URL => 'http://localhost/ci_test/index.php/api/new_category?title=entertainment&created_at=24-6-2017&image='.$encoded_img.'',
			CURLOPT_URL => 'http://localhost/ci_test/index.php/api/new_category',
			CURLOPT_USERAGENT => 'Mozilla/4.0',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				'title' => 'Entertainment',
				'created_at' => '8-10-1993',
				'image' => $encoded_img
			)
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		//echo $resp;
		// Close request to clear up some resources
		curl_close($curl);
	}
	function registration($username=null,$fullname=null,$password=null){
		if($username==null && $fullname==null && $password==null){
			$username = (isset($_REQUEST['username']))?$_REQUEST['username']:null;
			$fullname = (isset($_REQUEST['fullname']))?$_REQUEST['fullname']:null;
			$password = (isset($_REQUEST['password']))?$_REQUEST['password']:null;
		}
		if($username!=null && $fullname!=null && $password!=null){
			$data['username'] = $username;
			$data['fullname'] = $fullname;
			$data['password'] = md5($password);
			$insert_user = $this->apimodel->insert_new_user($data);
		}
		if(!empty($insert_user)){
			$response = array(
				'username' => $username,
				'id'       => $insert_user,
				'status'   => 'Success'
			);
		}else{
			$response = array(
				'username' => '',
				'id'       => '',
				'status'   => 'Fail'
			);
		}
		if(!empty($response)){
			header('Content-type:application/json');
			echo json_encode($response);
		}
	}
	function user_login(){
		//echo $this->input->post('request_data');
		$json = file_get_contents('php://input');
		$data = json_decode($json,true);
		$username = $data['username'];
		$password = $data['password'];
		$username = (isset($_REQUEST['username']))?$_REQUEST['username']:$username;
		$password = (isset($_REQUEST['password']))?md5($_REQUEST['password']):md5($password);
		$check_user = $this->apimodel->check_user_info($username,$password);
		if(count($check_user) >= 1){
			$response = array(
				'username' => $check_user['username'],
				'fullname' => $check_user['fullname'],
				'user_id'  => $check_user['id'],
				'last_login_time' => date("Y-m-d h:i:s"),
				'status' => 'Success'
			);
		}else{
			$response = array(
				'username' => '',
				'fullname' => '',
				'user_id'  => '',
				'last_login_time' => '',
				'status' => 'Fail'
			);
		}
		if(!empty($response)){
			header('Content-type:application/json');
			echo json_encode($response);
		}
	}
	function login_by_curl($username=null,$password=null){
		if($username !=null && $password !=null){
			$user_info = array(
				'username' => $username,
				'password' => $password
			);
			$request_data = json_encode($user_info);
		}
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => false,
			CURLOPT_HTTPHEADER => array(
								'Content-Type: application/json'
			),
			CURLOPT_URL => 'http://localhost/ci_test/index.php/api/user_login',
			CURLOPT_USERAGENT => 'Codular Sample cURL Request',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $request_data
			)
		);
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);
	}
}