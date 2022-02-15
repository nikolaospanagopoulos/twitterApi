<?php
include_once '../Backend/init.php';
include_once './getTweetIds.php';
if (isset($_POST['name']) && strlen($_POST['name']) > 0) {
    echo '<h1>Starting</h1>';
    $name = $_POST['name'];
    $max_results = $_POST['max_results'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];



    $result = getTweetIds($name, $max_results, $start_date, $end_date);

    $tweetArray = [];
    foreach ($result[0] as $key => $data) {





        if (isset($data['data'])) {

            $tweetArray[] = $data['data'];
        } elseif (isset($data[$key])) {

            foreach ($data[$key] as $newArray) {

                if (isset($newArray['data'])) {
                    $tweetArray[] = $newArray['data'];
                }
            }
        }
    }


    $tweetIds = [];
    if (count($tweetArray) > 0) {
        foreach ($tweetArray as $tweetData) {

            foreach ($tweetData as $tweet) {
                echo "<pre>";
                print_r($tweet['id']);
                $tweetIds[] = $tweet['id'];
                echo "</pre>";
            }
        }
    } else {
        echo "<h1>Not available data</h1>";
        die();
    }



    foreach ($tweetIds as $tweet) {
        print_r($tweet);
        $tweetId = $tweet;
        $newURL = 'https://api.twitter.com/2/tweets/?ids=' . $tweetId . '&tweet.fields=created_at,referenced_tweets,public_metrics&expansions=attachments.media_keys,entities.mentions.username&media.fields=duration_ms,height,media_key,preview_image_url,public_metrics,type,url,width,alt_text';

        $newResponse = $dataHandling->getTweetData($newURL);

        $tweetText = $newResponse->data[0]->text;

        $tweetId = $newResponse->data[0]->id;
        $media = "";
        if (isset($newResponse->includes->media)) {
            $tweetMedia = $newResponse->includes->media;

            foreach ($tweetMedia as $mediaKey => $url) {
                if (!isset($url)) {
                    $url = '';
                }
                $media .= $url->url ? $url->url . '    ' : $url->preview_image_url . '    ';
            }
        }





        $mentions = '';
        if (isset($newResponse->data[0]->entities)) {
            $tweetEntities = $newResponse->data[0]->entities->mentions;
            foreach ($tweetEntities as $entityKey => $mention) {
                $mentions .= $mention->username . ' ';
            }
        }

        $createdAt = $newResponse->data[0]->created_at;
        $retweets = $newResponse->data[0]->public_metrics->retweet_count;
        $replies = $newResponse->data[0]->public_metrics->reply_count;
        $likes = $newResponse->data[0]->public_metrics->like_count;
        $createdAt = strtok($createdAt, "T");
        $tweetId = (int)substr($tweetId, 0, 9);

        echo $dataHandling->showData($tweetText, $name, $createdAt);


        $dataHandling->insertDb(['tweetText' => $tweetText,  'tweetId' => $tweetId, 'mentions' => $mentions, 'media' => $media, 'user' => $name, 'retweets' => $retweets, 'replies' => $replies, 'likes' => $likes, 'createdAt' => $createdAt], $tweetId);
    }
    echo '<h1>Complete!</h1>';
}
