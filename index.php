<?php

/***********************************************
 * 
 * PHP-Youtube Downloader
 * 
 * Owner: Yehuda Eisenberg.
 * 
 * Mail: Yehuda.telegram@gmail.com
 * 
 * Link: https://yehudae.ga
 * 
 * Telegram: @YehudaEisenberg
 * 
 * GitHub: https://github.com/YehudaEi
 *
 * License: MIT - אסור לעשות שימוש ציבורי, חובה להשאיר קרדיט ליוצר
 * 
************************************************/

$url = parse_url($_SERVER['REQUEST_URI']);
if(strpos($url['path'], "movie.mp4") !== (strlen($url['path']) - 9))
    header("Location: ".substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/"))."/movie.mp4");

if(!isset($_GET['url'])){
    print '<html><head><title>PHP Youtube</title>'.
        '<script>function watch(){if(url=document.getElementById("url").value,null!==document.getElementById("video")){var e=document.getElementById("video");e.parentNode.removeChild(e);watch()}else{var t=document.createElement("video");t.setAttribute("src","?id="+url),t.setAttribute("id","video"),t.setAttribute("controls","controls"),t.setAttribute("autoplay","autoplay"),document.getElementById("content").appendChild(t)}}</script>'.
        '</head><body dir="rtl"><div style="visibility: hidden;"></body></div><center id="content"><h1>PHP Youtube Downloader</h1><input type="text" style="width:180" placeholder="Here is the link to YouTube :)" id="url"><br><br><button onclick="watch()">watch</button><br><br></center></body></html>'; 
    die();
}
else{
    $q = parse_url($_GET['url'], PHP_URL_QUERY);
    $q = explode("&", $q);
    
    foreach ($q as $str){
        if(strpos($str, "v=") === 0){
            $id = str_replace("v=", "", $str);
            break;
        }
    }
    
    if(isset($id) && !empty($id)){
        require_once('YTDL.php');
        $yt = new YouTubeDownloader();
        
        $file = 'logs' . '/' . date('Y-m-d') . '.log';
        $write = str_pad($_SERVER['REMOTE_ADDR'] . ', ' , 15) . date('d/M/Y - H:i:s') . ', ' . 'YouTube: '.$id . "\r\n";
        file_put_contents($file, $write, FILE_APPEND);
        
        $yt->stream("https://www.youtube.com/watch?v=".$id);
    }
    else
        die("'url' not found!");
}
