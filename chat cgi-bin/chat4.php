<?php
/**
 * Simple chat example 
 * Adapted from code at http://arkanis.de/projects/simple-chat/
 */

// Name of the message buffer file. You have to create it manually with read and write permissions for the webserver.
$messages_buffer_file = 'messages4.json';
// Number of most recent messages kept in the buffer
$messages_buffer_size = 10;

if ( isset($_POST['content']) and isset($_POST['name']) )
{
    // Open, lock and read the message buffer file
    $buffer = fopen($messages_buffer_file, 'r+b');
    flock($buffer, LOCK_EX);
    $buffer_data = stream_get_contents($buffer);
    
    // Append new message to the buffer data or start with a message id of 0 if the buffer is empty
    $messages = $buffer_data ? json_decode($buffer_data, true) : array();
    $next_id = (count($messages) > 0) ? $messages[count($messages) - 1]['id'] + 1 : 0;
    $messages[] = array('id' => $next_id, 'time' => time(), 'name' => $_POST['name'], 'content' => $_POST['content']);
    
    // Remove old messages if necessary to keep the buffer size
    if (count($messages) > $messages_buffer_size)
        $messages = array_slice($messages, count($messages) - $messages_buffer_size);
    
    // Rewrite and unlock the message file
    ftruncate($buffer, 0);
    rewind($buffer);
    fwrite($buffer, json_encode($messages));
    flock($buffer, LOCK_UN);
    fclose($buffer);
    
    // Optional: Append message to log file (file appends are atomic)
    //file_put_contents('chatlog.txt', strftime('%F %T') . "\t" . strtr($_POST['name'], "\t", ' ') . "\t" . strtr($_POST['content'], "\t", ' ') . "\n", FILE_APPEND);
    
    exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Counselor Chat</title>
    <style type="text/css">
      html { margin: 0em; padding: 0; }
        body { margin: 2em; padding: 0; font-family: sans-serif; font-size: medium; color: #333; }
        h1 { margin: 0; padding: 0; font-size: 2em; font-family: 'Oswald', sans-serif;
                font-weight: 400; }
        p.subtitle { margin: 0; padding: 0 0 0 0.125em; font-size: 0.77em; color: gray; }
        
        ul#messages { overflow: auto; height: 21em; margin: 1em 0; padding: 0 3px; list-style: none; border: 1px solid gray; }
        ul#messages li { margin: 0.35em 0; padding: 0; }
        ul#messages li small { display: block; font-size: 0.59em; color: gray; }
        ul#messages li.pending { color: #aaa; }
        
        form { font-size: 1em; margin: 1em 0; padding: 0; }
        form p { position: relative; margin: 0.5em 0; padding: 0; }
        form p input { font-size: 1em; }
        form p input#name { width: 10em; }
        form p button { position: absolute; top: 0; right: -0.5em; }
        
         ul#messages, form p { width: 80%; }
      
        
     .btn {
        height: 86px;
            background-color: #1FA5AB;
            color: #fff;
            text-align: center;
            padding: 7px 20px 8px;
            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            -o-border-radius: 5px;
            -ms-border-radius: 5px;
            border: none; 
            font-family: 'Oswald', sans-serif;
            font-weight: 400;
            font-size: .75em;
        }
        a {
             text-decoration: none;
        }
        #backToForum {
            margin-top: 10px;
            margin-bottom: 30px;
        }
        body {
            min-width: 650px;
        }
        #imageInfo, #titleInfo{
            float: left;
        }

        #imageInfo {
            margin-right: 15px;
        }

        #infoContainer {
            position: relative;
            height: 150px;
        }

        #titleInfo {
                margin-top: 37.5px;
        }
        #totalBody {
            height: 100%;
            width:100%;
        }
      html, body {
        width: 100%;
         height: 100%;
        
        }

        .info {
            position: relative;
            height: auto;
            width: 100%;
        }
        ul#messages {
            width: 80%;
     
        }

        #content {
            height: 5em;
            width: 90%;
        }


        pre { font-size: 0.77em; }
    </style>
    <meta name="author" content="Stephan Soller" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
        <script type="text/javascript" src="js/script5.js"></script>

<div id="backToForum"> <a href = "http://stanford.edu/~kartiks2/cgi-bin/chat/chatHome.php" class ="btn"> Back to Forum </a> </div>
<div id="infoContainer">
<div id="imageInfo"><a href="https://therapists.psychologytoday.com/rms/prof_detail.php?profid=33244&sid=1456803127.1468_25177&city=Oakland&county=Alameda&state=CA&spec=334&tr=ResultsPhoto"><img src="img/WilliamSpivey.jpg"></a></div>
<div id="titleInfo"><h1>Chat with William L. Spivey, PhD</h1>
<p class="subtitle">Up to ten messages from your previous chat session with this counselor are displayed here. <br>Happy chatting!</p></div>
</div>

<ul id="messages">
    <li>loading…</li>
</ul>

<form action="<?= htmlentities($_SERVER['PHP_SELF'], ENT_COMPAT, 'UTF-8'); ?>" method="post">
    <p>
        <input type="text" name="content" id="content" /><button type="submit" class="btn">Send</button>
    </p>
    <p>
        <label>
            Name:
            <input type="text" name="name" id="name" value="Anonymous" />
        </label>
        
    </p>
</form>
</body>
</html>