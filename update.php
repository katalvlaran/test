<?php

require_once 'simple_html_dom.php';

if($_GET['update'] == 1){
    $class = new NewsParse();
    $parse = $class->getParseNews();
}

class NewsParse {

    public $connect;

    public function __construct()
    {
        $this->connect = mysqli_connect('127.0.0.1', 'root', '', 'pars_task');
    }

//    public function __destruct()
//    {
//        mysqli_close($this->connect);
//    }

    public function getLinks()
    {
        $result = mysqli_query($this->connect, "SELECT * FROM news") or die(mysqli_error($this->connect));
        $links = $result->fetch_all();
        return $links;
    }


    public function getHeaders ($url, $refer ='http://www.google.com')
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $refer);
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $text = curl_exec($curl);
        curl_close($curl);
        return $text;
    }

    public function getParseNews()
    {
        $url = 'http://korrespondent.net/ukraine/';
        $html = file_get_html('http://korrespondent.net/ukraine/');
        foreach($html->find('div.article__title', 0)->find('a') as $a){
            $news_name = $a->innertext;
            $news_link = $a->href;
        }

        $log = $this->getHeaders($url);
        file_put_contents('log.txt', $log);

        $result = mysqli_query($this->connect, "SELECT * FROM `news` ORDER BY `id` DESC LIMIT 1") or die(mysqli_error($this->connect));
        $last_link = $result->fetch_assoc();
        if ($last_link['name'] !== $news_name){
            $news_date = date('Y-m-d H:i:s');
            $insert = "INSERT INTO `news` (`name`, `link`, `update`) VALUES ('" .$news_name. "', '" .$news_link. "', '" . $news_date . "')";
            $result = mysqli_query($this->connect, $insert) or die(mysqli_error($this->connect));
        }
    }
}

