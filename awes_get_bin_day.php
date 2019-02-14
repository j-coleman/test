<?php
function bin_dates($house, $postcode){
	$bin_dates="";
	$data = '{
		"payload":{
		"function":"dialogflow_test",
		"data":[
		  {
			"postcode":"bn43 6pj",
			"address_line_1":"6"
		  }
		]
		}
	}
	';
	$mats_url ="https://adur-worthing-awcs.onmats.com/api/REST/mats_app/1.0";

	function mats_post($url, $post){
	 $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	  curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',
		'API-Authentication: d7bde937b0c95d26124c3f2641fb5651',
		'Content-Length: ' . strlen($post))                                                                    
	   );  
	  $result = curl_exec($ch);
	  curl_close($ch);
	  return $result;
	}

	$mats_response = mats_post($mats_url, $data);

	$mats_array = new RecursiveIteratorIterator(
	new RecursiveArrayIterator(json_decode($mats_response, TRUE)),
	RecursiveIteratorIterator::SELF_FIRST);

	foreach ($mats_array as $key => $val) {
		if(is_array($val)) {
		} else {
			if($key == 'refuse_day_1'){
			$ref_day_1 = date_format($val, 'l jS F');
			}
			if($key == 'recycling_day_1'){
			$rec_day_1 = date_format($val, 'l jS F');
			}
		}
	}

    if(isset($ref_day_1)){
     $bin_dates= "Your next refuse collection day is " . $ref_day_1 . " and your recycling will be collected on " . $rec_day_1;
        $arr=array(
            "source" => "Adur & Worthing Councils",
            "fulfillmentText" => $bin_dates
        );
        sendMessage($arr);
    }else{
        $arr=array(
            "source" => "Adur & Worthing Councils",
            "fulfillmentText" => "We couldn't find " . $house . ", " . $postcode . ". Find your local authority at www.gov.uk/find-local-council"
        );
        sendMessage($arr);
    }
}

function bin_calendar($house, $postcode){
    $bin_calendar="";
    $bin_calendar= "You can download your waste collection calendar from " . $row["round_cal"] . "I can also tell you when your next bin collection date is.";
	if(isset($postcode)) {
        $arr=array(
            "source" => "Adur & Worthing Councils",
            "fulfillmentText" => $bin_calendar
        );
        sendMessage($arr);
    }else{
        $arr=array(
            "source" => "Adur & Worthing Councils",
            "fulfillmentText" => "We couldn't find that address. Find your local authority at www.gov.uk/find-local-council"
        );
        sendMessage($arr);
    }
}
?>