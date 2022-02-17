<?php
include_once '../Backend/init.php';
error_reporting(E_ALL); // Error/Exception engine, always use E_ALL

ini_set('ignore_repeated_errors', TRUE); // always use TRUE

ini_set('display_errors', TRUE); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', TRUE); // Error/Exception file logging engine.
ini_set('error_log', 'your/path/to/errors.log'); // Logging file path
set_time_limit(0);
function getTweetIds($name, $maxResults, $start_date, $end_date)
{
    $ids = [];


    $starttime = date("c", strtotime($start_date . " 00:00:00"));
    $endtime = date("c", strtotime($end_date . " 00:00:00"));

    global $dataHandling;
    $dataHandling = new DataHandling($name);
    $userId = $dataHandling->getUserId();


    $api = "https://api.twitter.com/2/users/" . $userId . "/tweets?start_time=".$start_date."T00:00:00.000Z&end_time=".$end_date."T00:00:00.000Z" . "&max_results=" . $maxResults;
    function getData($api, $dataHandling)
    {

        $array[] = $dataHandling->getTweetId($api);

        foreach ($array as $key => $data) {

            if (isset($array[$key]['meta']['next_token'])) {

                $result = explode("&pagination_token=", $api)[$key];

                $array[$key] = array_merge($array, (getData($result . "&pagination_token=" . $array[$key]['meta']['next_token'], $dataHandling)));
            }
        }
        return $array;
    }
    return getData($api, $dataHandling);
}
