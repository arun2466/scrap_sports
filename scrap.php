<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once 'phpQuery-onefile.php';

  class SCRAP{
    public static function getHtml( $url ){
      $timeout = 10; // set to zero for no timeout
      $ch = curl_init($url); // initialize curl with given url
      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]); // set  useragent
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // max. seconds to execute
      curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
      $html = @curl_exec($ch);
      return $html;
    }

    public static function scrapWebsite( $website, $url){
      $html = self::getHtml($url);
      phpQuery::newDocumentHTML($html);

      $homeTeam = $awayTeam = $homeTeam_score = $awayTeam_score = '';

      if($website=='skysports'){
        if( sizeof(pq('.match-head__team-name')) > 0 ){
          foreach(pq('.match-head__team-name') as $div){
            if( $homeTeam == ''){
              $homeTeam = pq($div)->find('abbr')->attr('title');
            } else{
              $awayTeam = pq($div)->find('abbr')->attr('title');
            }
          }
        }

        if( sizeof(pq('span.match-head__score')) > 0 ){
          foreach(pq('span.match-head__score') as $div){
            if( $homeTeam_score == ''){
              $homeTeam_score = pq($div)->text();
            } else{
              $awayTeam_score = pq($div)->text();
            }
          }
        }

      } else if($website=='bbc'){
        if( sizeof(pq('.fixture__team-name-trunc')) > 0 ){
          foreach(pq('.fixture__team-name-trunc') as $div){
            if( $homeTeam == ''){
              $homeTeam = pq($div)->attr('title');
            } else{
              $awayTeam = pq($div)->attr('title');
            }
          }
        }

        if( sizeof(pq('.fixture__number--home')) > 0 ){
          $homeTeam_score = pq('.fixture__number--home')->text();
        }
        if( sizeof(pq('.fixture__number--away')) > 0 ){
          $awayTeam_score = pq('.fixture__number--away')->text();
        }
      }

      return array(
        'homeTeam' => trim($homeTeam),
        'awayTeam' => trim($awayTeam),
        'homeTeam_score' => trim($homeTeam_score),
        'awayTeam_score' => trim($awayTeam_score),
      );

    }

  }





 $data1 = SCRAP::scrapWebsite('skysports','http://www.skysports.com/football/tottenham-vs-arsenal/live/373365');
 // $data2 = SCRAP::scrapWebsite('bbc','http://www.bbc.com/sport/live/football/40955525');

 echo '<pre>';
 print_r($data1);
 // print_r($data2);

?>