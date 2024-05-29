<?php
class simplePushNotification
{
    private $apiUrl = 'https://api.simplepush.io/send';

    public function sendNotification($key, $title, $message)
    {
        $data = array(
            'key' => $key,
            'title' => $title,
            'msg' => $message
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($this->apiUrl, false, $context);

        if ($result === false) {
            // Handle error
            return false;
        }

        return $result;
    }
}