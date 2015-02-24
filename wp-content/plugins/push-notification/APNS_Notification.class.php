<?php

class APNS_Notification {

    function sendMessageToDeviceComponent($token_devices, $message, $badge, $other_param) {
        $flg = 2;//1: DEV; 2: dis. You have to config here.
        // Put your device token here (without spaces):
        $deviceToken = $token_devices;

        // Put your private key's passphrase here:
        $passphrase = 'umacure2014';

        ////////////////////////////////////////////////////////////////////////////////

        $ctx = stream_context_create();
        if($flg == 1) {
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'APN_DEV.pem');
        }else {
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'APN_DIS.pem');
        }
        
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        if($flg == 1) {
            $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        }else {
            $fp = stream_socket_client(
                'ssl://gateway.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        }
        

        //if (!$fp)
                //exit("Failed to connect: $err $errstr" . PHP_EOL);

        //echo 'Connected to APNS' . PHP_EOL;

        // Create the payload body
        $body['aps'] = array(
                'alert' => $message,
                'badge' => $badge,
                'sound' => 'default'
                );
        $body['article_id'] = $other_param['article_id'];
        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        //if (!$result)
        //        echo 'Message not delivered' . PHP_EOL;
        //else
        //        echo 'Message successfully delivered' . PHP_EOL;

        // Close the connection to the server
        fclose($fp);

    }
}
?>
