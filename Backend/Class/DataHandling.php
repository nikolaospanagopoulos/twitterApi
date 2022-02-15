<?php
include './Database.php';

class DataHandling
{
    private $userName;

    private $getUserUrl = "https://api.twitter.com/2/users/by?usernames=";
    private $options = array('http' => array(
        'method' => "GET",
        'header' => 'Authorization:Bearer ' . AUTH_TOKEN
    ));
    public function __construct($userName)
    {
        $this->userName = $userName;
        $this->db = Database::instance();
    }


    public function getUserId()
    {
        $options = stream_context_create($this->options);
        $response = file_get_contents($this->getUserUrl . $this->userName, false, $options);
        $response = json_decode($response);
        return $response->data[0]->id;
    }

    public function getTweetId($getTweetIdUrl)
    {
        $options = stream_context_create($this->options);
        $response = file_get_contents($getTweetIdUrl, false, $options);
        $response = json_decode($response,true);
        return $response;
    }


    public function getTweetData($getTweetDataUrl)
    {
        $options = stream_context_create($this->options);
        $response = file_get_contents($getTweetDataUrl, false, $options);
        $response = json_decode($response);
        return $response;
    }

    public function insertDb($fields = array(), $tweetId)
    {
        $columns = implode(", ", array_keys($fields));
        $values = ":" . implode(", :", array_keys($fields));


        $sql = "INSERT INTO `tweets` ({$columns})  VALUES (${values}) 
     
         ";


        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':tweetId', $tweetId);
            foreach ($fields as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            $stmt->execute();
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function getMetrics($metricsUrl)
    {
        $options = stream_context_create($this->options);
        $response = file_get_contents($metricsUrl, false, $options);
        $response = json_decode($response);
        return $response;
    }

    public function showData($text, $userName, $date)
    {
        return "
        <h2>" . $userName . "</h2>
        <h3>" . $text . "</h3>
        <h4>" . $date . "</h4>
        ";
    }
}
