<?php
include_once '../Backend/init.php';

if (isset($_POST['name']) && strlen($_POST['name']) > 0) {
    echo '<h1>Starting</h1>';
    $name = $_POST['name'];
    $max_results = $_POST['max_results'];
    $start_date = $_POST['start_date'] . "T00:00:00Z";
    $end_date = $_POST['end_date'] . "T00:00:00Z";
    $dataHandling = new DataHandling($name);
    $userId = $dataHandling->getUserId();

    $url = "https://api.twitter.com/2/users/" . $userId . "/tweets?start_time=" . $start_date . "&end_time=" . $end_date . "&max_results=" . $max_results;
    $response = $dataHandling->getTweetId($url);
    foreach ($response->data as $key => $tweet) {
        $tweetId = $tweet->id;
        $newURL = 'https://api.twitter.com/2/tweets/?ids=' . $tweetId . '&tweet.fields=created_at,referenced_tweets,public_metrics&expansions=attachments.media_keys,entities.mentions.username&media.fields=duration_ms,height,media_key,preview_image_url,public_metrics,type,url,width,alt_text';

        $newResponse = $dataHandling->getTweetData($newURL);

        $tweetText = $newResponse->data[0]->text;

        $tweetId = $newResponse->data[0]->id;
        $tweetMedia = $newResponse->includes->media;

        $media = "";
        foreach ($tweetMedia as $mediaKey => $url) {

            $media .= $url->url ? $url->url . ' ' : $url->preview_image_url . ' ';
        }

        $mentions = '';
        if (isset($newResponse->data[0]->entities)) {
            $tweetEntities = $newResponse->data[0]->entities->mentions;
            foreach ($tweetEntities as $entityKey => $mention) {
                $mentions .= $mention->username . ' ';
            }
        }
        $retweets = $newResponse->data[0]->public_metrics->retweet_count;
        $replies = $newResponse->data[0]->public_metrics->reply_count;
        $likes = $newResponse->data[0]->public_metrics->like_count;

        $tweetId = (int)substr($tweetId, 0, 8);
        print_r($tweetId);
        $dataHandling->insertDb(['tweetText' => $tweetText, 'tweetId' => $tweetId, 'mentions' => $mentions, 'media' => $media, 'user' => $name, 'retweets' => $retweets, 'replies' => $replies, 'likes' => $likes], $tweetId);
    }
    echo '<h1>Complete</h1>';
}
