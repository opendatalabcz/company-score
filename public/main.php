<?php

require  __DIR__ . "/../vendor/autoload.php";
use App\Firm;
use App\Score;
use App\Scrap;

include 'phpWhoisClass/src/whois.main.php';

 function generateRandomNumber($length = 8)
{
    $random = "";
    srand((double) microtime() * 1000000);

    $data = "123456123456789071234567890890";

    for ($i = 0; $i < $length; $i++) {
        $random .= substr($data, (rand() % (strlen($data))), 1);
    }

    return $random;

}

$array_atr=array("Nazev","ICO","Sidlo","Status(v likvidaci)","Pocet let na trhu","Pocet zamestnancu","Nespolehlivy platce","Pravni forma",
    "Rust zakladniho kapitala","Neduveryhodny vek jednatele","Pocet jednatelu","Bydleni mimo EU","Pocet jinych subjektu","Pocet jinych subjektu v likvidaci",
    "Dluzniky","Oz","Status domeny","Domena(let v provozu)","Jine subjekty na sidle","Volne nabidky prace","Test zakladni","Test jednatelu","Pocet provozoven",
    "Test samotneho subjekta","Test domeny","Test bonusovy","Vysledek");

$arr= array();
for ($i=0;$i<12000;++$i)
{
    array_push($arr,generateRandomNumber());
    //array_push($arr,str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT));
}

foreach ($arr as $qwe) {
    $firma = strval($qwe);
    print $firma."\n";
    $result_array=array();
    $scrap=new Scrap();
   $result_array=$scrap->main_info($result_array,$qwe);
    sleep(mt_rand(2, 6));
    if(!isset($result_array['Status(v likvidaci)']))
    {
        continue;
    }
    if ($result_array['Status(v likvidaci)'] === 'NE' ) {

        $result_array=$scrap->pocet_zamestnancu($result_array);
        $result_array=$scrap->dph($result_array);
        $result_array=$scrap->pravni_forma($result_array);
        $result_array=$scrap->zakladni_kapital($result_array);
        $result_array=$scrap->people_info($result_array);
        $result_array=$scrap->bydleni_jednatelu($result_array);
        $result_array=$scrap->ochranne_znamky($result_array);
        $result_array=$scrap->domena_info($result_array);

        $whois = new Whois();
        $result = $whois->Lookup($scrap->getDm());
        $result_array=$scrap->whois_parser($result,$result_array);

        $result_array=$scrap->pocet_provozoven($result_array);
        $result_array=$scrap->jine_firmy_na_sidle($result_array);
        $result_array=$scrap->nabidky_prace($result_array);
    }

    foreach ($array_atr as $item)
    {
        if(!isset($result_array[$item]))
            $result_array[$item]=null;
    }


    Firm::createTable();
    Firm::insertion($result_array['Nazev'],$result_array['ICO'],$result_array['Sidlo'],$result_array['Status(v likvidaci)'],
    $result_array['Pocet let na trhu'],$result_array['Pocet zamestnancu'],$result_array['Pravni forma'],$result_array['Nespolehlivy platce'],
    $result_array['Rust zakladniho kapitala'],$result_array['Pocet jednatelu'],$result_array['Neduveryhodny vek jednatele'],$result_array['Bydleni mimo EU'],
    $result_array['Pocet jinych subjektu'],$result_array['Pocet jinych subjektu v likvidaci'],$result_array['Dluzniky'],$result_array['Oz'],
    $result_array['Status domeny'],$result_array['Domena(let v provozu)'],$result_array['Pocet provozoven'],$result_array['Jine subjekty na sidle'],
    $result_array['Volne nabidky prace'],$result_array['Test zakladni'],$result_array['Test jednatelu'],$result_array['Test samotneho subjekta'],
    $result_array['Test domeny'],$result_array['Test bonusovy'],$result_array['Vysledek']);


    $score=new Score();
    $score->Zakladni_test_count($result_array['ICO']);
    $score->Jednatelu_test_count($result_array['ICO']);
    $score->Subjekt_test_count($result_array['ICO']);
    $score->Domena_test_count($result_array['ICO']);
    $score->Bonusovy_test_count($result_array['ICO']);
    $score->Vysledek($result_array['ICO']);


//     print_r($result_array);

}


//$array=Firm::findAll();
//foreach ($array as $item)
//{
//    print $item."\n";
//    $score=new Score();
//    $score->Zakladni_test_count($item);
//    $score->Jednatelu_test_count($item);
//    $score->Subjekt_test_count($item);
//    $score->Domena_test_count($item);
//    $score->Bonusovy_test_count($item);
//    $score->Vysledek($item);
//}
