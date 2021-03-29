<?php

require  __DIR__ . "/../vendor/autoload.php";
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;



$base='https://katalog-firem.net/';
$client=new Client();
$start=array();

$crawler=$client->request("GET",$base);
$links = $crawler->filter('#columnRight > .bigCategoryList > .categoryItem')->each(function (Crawler $node)
{
    return $node->filter('a')->link();
});
foreach ($links as $link)
{
    $crawler=$client->click($link);
    $sec_page=$crawler->filter('.paging a:nth-child(6)')->link();
    $crawler=$client->click($sec_page);
    if($crawler->filter('.paging a:nth-child(9)')->count()>0)
    {
        $sec_page=$crawler->filter('.paging a:nth-child(9)')->link();
        $crawler=$client->click($sec_page);
        if($crawler->filter('.paging a:nth-child(9)')->count()>0)
        {
            $sec_page=$crawler->filter('.paging a:nth-child(9)')->link();
            $crawler=$client->click($sec_page);
            if($crawler->filter('.paging a:nth-child(10)')->count()>0)
            {
                $sec_page=$crawler->filter('.paging a:nth-child(10)')->link();
                $crawler=$client->click($sec_page);
                if($crawler->filter('.paging a:nth-child(8)')->count()>0)
                {
                    $sec_page=$crawler->filter('.paging a:nth-child(8)')->link();
                    $crawler=$client->click($sec_page);
                }
            }

        }
    }
    $inside_links=$crawler->filter('#columnRight  .advertItem  h2 ')->each(function(Crawler $node)
    {
        return $node->filter('a')->link();
    });
    foreach ($inside_links as $in)
    {
        $crawler=$client->click($in);
        if($crawler->filter('#detail  > .left > .advertInfo:nth-child(4) tr:nth-child(1) td')->count()>0)
        {
            $ico=$crawler->filter('#detail  > .left > .advertInfo:nth-child(4) tr:nth-child(1) td')->text();
            $ico=trim($ico);
            if(strlen($ico)>=6 &&strlen($ico)<=8)
            array_push($start,$ico);
        }

    }
}

$txt = print_r($start, true);
file_put_contents("icoS.txt", $txt);



?>
