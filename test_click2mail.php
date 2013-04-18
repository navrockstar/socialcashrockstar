<?php
// session_start();
error_reporting(E_ALL);
 include_once 'Api/Click2mail_Mailingonline_Api.php';
 $url = "https://stage-mailingonline.click2mail.com";
// $username = "navyarockstar";
// $password = "Lordkrishna123";
$username = $_POST['txtuname'];;
$password = $_POST['txtpwd'];
//$path_to_pdf = "fixtures/pdf/sample_pdf.pdf";
//$path_to_csv = "fixtures/csv/sample_pdf.pdf";
$path_to_pdf = "fixtures/pdf/test.pdf";
$csv = array();
$add=array();

if($_FILES['csv']['error'] == 0){
    $name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
   $type = $_FILES['csv']['type'];
    $tmpName = $_FILES['csv']['tmp_name'];

    // check the file is a csv
    if($ext === 'csv'){
        if(($handle = fopen($tmpName, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;

            while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // number of fields in the csv
                $num = count($data);

                // get the values from the csv
                   $csv[$row]['NAME'] = $data[0];
                   $csv[$row]['ADDRESS'] = $data[1];
		   $csv[$row]['CITY']  = $data[2];
		   $csv[$row]['STATE']  = $data[3];
		   $csv[$row]['ZIP']  = $data[4];		
		   foreach($csv[$row] as $name=>$value){
			 $add[$row][] = array('name'=>$name,'value'=>$value);
			
		   }
                // inc the row
                $row++;
            }

            fclose($handle);
        }
    }
	//print_r($csv);
}

$data=$add;





// $name		=	$_POST['txtname'];
// $address	=	$_POST['txtadd'];
// $city		=	$_POST['txtcity'];
// $state		=	$_POST['txtstate'];
// $zip		=	$_POST['txtzip'];
// $add = array("NAME" => $name , "ADDRESS" => $address,"CITY"=>$city,"STATE"=>$state,"ZIP"=>$zip) ;
// 



//$all = $_SESSION['all'];

//$all[] = $add ;

//$_SESSION['all'] = $all;


//echo '<pre>';
//print_r($all);


$new_document_name = "upload_doc_" . time();
$image_file_type = array('PDF' => 2);

$api = new Click2mail_Mailingonline_Api($url, $username, $password);
 //Document ID = 40128521   Data List Id= 40151246   Data Template Id= 40015316
//Define document template id
$document_template_id =  40128838; // Document id from the proof page
$data_template =40015385 ; // Data template id from the proof page.
//Information for API Users: Document ID = 40128838   Data List Id= 40151806   Data Template Id= 40015385
//Upload PDF file using HTTP method
 $uploaded_file_id = $api->UploadDocument($path_to_pdf);
$response = $api->CreateDocumentFromTemplate($new_document_name, $document_template_id, $image_file_type['PDF'], $uploaded_file_id);
//var_dump($response);
$document_creation_token = $response->document_creation_token;

$status_id = 0;

try{
	while($status_id != 2){

		$response = $api->CheckDocumentCreateStatus($document_creation_token);

		$status_id = $response->document_creation_status->status_id;

		if($status_id == 3){
			throw new Exception("status_id is 3 - there is an error");
		}
		sleep(30);
	}
}catch(Exception $e){
	echo $e->getMessage();
}


try{
	$response = $api->CompleteDocumentCreation($document_creation_token);
}catch(Exception $e){
	echo $e->getMessage();

}

echo "docid". $document_id = $response->document_id;
/*$data = array(0 => array(
						array('name' => 'NAME', 'value' => 'Some Guy'),
						array('name' => 'ADDRESS', 'value' => '123 Spring St.'),
						array('name' => 'CITY', 'value' => 'Arlington'),
						array('name' => 'STATE', 'value' => 'VA'),
						array('name' => 'ZIP', 'value' => '22902')
						),						
			  1 => array(
						array('name' => 'NAME', 'value' => 'Other Guy'),
						array('name' => 'ADDRESS', 'value' => '456 Main St.'),
						array('name' => 'CITY', 'value' => 'Ridgefield'),
						array('name' => 'STATE', 'value' => 'CT'),
						array('name' => 'ZIP', 'value' => '26810')
						));*/

//$data=$all;
//$data = $csv;

try{
	$response = $api->CreateDataList($data_template, $data);


}catch(Exception $e){
	echo $e->getMessage();
}

$list_id = $response->list_id;
echo "listid".$list_id;
$list_status = 0;
try{
	while($list_status != 5){

		$response = $api->CheckListStatus($list_id);
		$list_status = $response->list_status->status_id;

		if($list_status == 9){
			throw new Exception('There is an error with processing your list.');
		}
		sleep(15);
	}
}catch(Exception $e){
	echo $e->getMessage();
}

try{
	$response = $api->SubmitPreview($document_id, $list_id);
}catch(Excpetion $e){
	echo $e->getMessage();
}

echo "preview".$preview_id = $response->preview_id;
$preview_status = 0;

try{
	while($preview_status != 2){
		$response = $api->CheckPreviewStatus($preview_id);
		$preview_status = $response->preview_status->status_id;

		if($preview_status == 3){
			throw new Exception("preview_status is 4 - there was an error");
		}
		sleep(15);
	}

}catch(Exception $e){
	echo $e->getMessage();
}

echo $response->pdf_url;
echo " ";

$mail_type_code = 'First Class';

$preferred_schedule_date = date('m/d/Y', strtotime('today') + 60 * 60 * 24);
$billing_details = array('bill_name' => 'Navya', //Required
						 'bill_address1' => 'abc,test home,US', //Required
						 'bill_city' => 'US', //Required
						 'bill_state' => 'Trivandrum', //Required
                         'bill_zip' => '69558', //Required
						 'bill_type' => 'Credit Card', //Required
					     'bill_number' => '4111111111111111', //Pass empty string if no value is required
                         'bill_exp_month' => '10', //Pass empty string if no value is required
						 'bill_exp_year' => '2020');  //Pass empty string if no value is required




$return_address = array('name' => 'Navya',
						'business' => '',
						'address' => 'abc,test home',
						'city' => 'US',
						'state' => 'Trivandrum',
						'zip' => '69558',
						'ancillary_endorsement' => ' ');		

$testing = false;

try{
	//$reponse = $api->SubmitJob($document_id, $list_id, $mail_type_code,$testing);
$reponse = $api->SubmitJob3($document_id, $list_id, $mail_type_code, $preferred_schedule_date, $billing_details, $return_address, $testing);
}catch(Exception $e){
	echo $e->getMessage();
}
$job_id = $reponse->job_id;

echo "Jobid".$job_id;
$job_status = 0;
try{
	while($job_status != 3){
		$response = $api->CheckJobStatus($job_id);
		$job_status = $response->job_status->status_id;
                  echo "jobstatus".$job_status;
if($job_status == 9){
			throw new Exception("job_status is 9 - there was an error");
		}
		sleep(15);		
}	

}catch(Exception $e){
	echo $e->getMessage();
}


