<?php
require 'awes_get_bin_day.php';

function processMessage($input) {
    $search = $input["queryResult"]["intent"]["displayName"];
    $house = $input["queryResult"]["parameters"]["number"];
    $postcode = $input["queryResult"]["parameters"]["zip-code"];
    //$postcode = preg_replace('/\s*/', '', $postcode); 
    $postcode = strtolower($postcode);
    switch($search){

        case "awes_days":
            bin_dates($house, $postcode);
            break;

        case "awes_calendar":
            bin_calendar($house, $postcode);
            break;

        default :
            sendMessage(array(
                "source" => "Adur & Worthing Councils",
                "speech" => "I didn't have that address on my database. Find your local authority at www.gov.uk/find-local-council",
                "displayText" => "I didn't have that address on my database. Find your local authority at www.gov.uk/find-local-council",
                "contextOut" => array()
            ));
    }
}
function sendMessage($parameters) {
    header('Content-Type: application/json');
    $data = str_replace('\/','/',json_encode($parameters));
    echo $data;
}
$input = json_decode(file_get_contents('php://input'), true);
if (isset($input["queryResult"]["intent"]["displayName"])) {
    processMessage($input);
}
?>