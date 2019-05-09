<?php
define("SLACK_ROOM_DEV4_CI", "/T0258EF9Y/BCBJBU0SJ/JI3Oyshx5o81RFrfziKfJjXo");

$req = array();

$longopts  = array(
    "room:",
    "message:",
    "color:",
    "type:"
);

$shortopts = "";

$arguments = getopt($shortopts, $longopts);

foreach ($arguments as $key => $value) {
    $req[$key] = trim($value);
}

$Notification = new Notification();

if($req['room']) {
    $Notification->sendMessage($req['room'], $req['message'], $req['color']);
}


class Notification {
    public function sendToSlack($url, $message, $color) {
        $arr['attachments'][0]['title'] = $message;
        $arr['attachments'][0]['color'] = $color;
        $data_string = json_encode($arr);print_r($data_string);exit;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => "https://hooks.slack.com/services{$url}",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data_string,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        ));
        curl_exec($curl);
        curl_close($curl);
    }

    public function sendMessage($roomid, $message, $color){
        $roomid = strtolower($roomid);

        switch($color) {
            case "purple":
                $slack_color = "#9900ff";
                break;
            case "red":
                $slack_color = "danger";
                break;
            case "yellow":
                $slack_color = "#ff9900";
                break;
            default:
                $slack_color = $color;
        }

        $pattern = "(<a href=(.*)(http[s]{0,1}[\w:/\.]*)['\"> ]{1,}(.*)</a>)";
        $slack_message = preg_replace($pattern, '<${2}|${3}>', $message);

        if ($roomid == "production_smoke") {
            self::sendToSlack(SLACK_ROOM_PRODUCTIONSMOKE, $slack_message, $slack_color);
        }
        elseif ($roomid == "dev4_ci") {
            self::sendToSlack(SLACK_ROOM_DEV4_CI, $slack_message, $slack_color);
        }
    }
}
?>