<?php

use App\Firm;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
include 'phpWhoisClass/src/whois.main.php';
require  __DIR__ . "/../vendor/autoload.php";


$result_array=array();
$firma=fgets(STDIN);

//-----------------------------------------------------------------------------------//
//Verejny
unset($crawler);
$client=new Client();
$base='https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY';
$crawler=$client->request("GET",trim($base));
$firma_replace1=$firma;
$firma_replace1=str_replace('s.r.o.','',$firma_replace1);
$firma_replace1=str_replace('a.s.','',$firma_replace1);
$firma_replace1=str_replace('v.o.s.','',$firma_replace1);
$form= $crawler->filter('.search-buttons button.button')->form();
$form->setValues([
    'nazev'=>$firma
]);
$crawler =$client->submit($form);
$all_names=$crawler->filter('#SearchResults > .section-c > .search-results > ol > li')->each(function (Crawler $li)
{
    $ico=trim($li->filter('.inner > .result-details > tbody tr')->first()->filter('td')->last()->text());
    $name=trim($li->filter('.inner > .result-details > tbody tr:nth-child(1) > th+td .left')->text());
    $sidlo=trim($li->filter('.inner > .result-details > tbody tr ')->last()->filter('td')->text());
    $pocet_let=trim($li->filter('.inner > .result-details > tbody tr:nth-child(even)')->first()->filter("td")->last()->text());
    return [
      'Nazev'=>$name,
      'ICO'=>$ico,
      'Sidlo'=>$sidlo,
      'Pocet let na trhu'=>$pocet_let
    ];
});
foreach ($all_names as $name)
{
    $tmp=$name['Nazev'];
    $tmp=str_replace('s.r.o.','',$tmp);
    $tmp=str_replace('a.s.','',$tmp);
    $tmp=str_replace('v.o.s.','',$tmp);
    $tmp=str_replace(',','',$tmp);
    $tmp=str_replace('v likvidaci','',$tmp);
    if(trim(strtolower($firma_replace1))===trim(strtolower($tmp)))
    {
        if(strpos($name['Nazev'],'v likvidaci')!==false)
        {
            $result_array['Nazev']=$name['Nazev'];
            $result_array['ICO']=$name['ICO'];
            $result_array['Sidlo']=$name['Sidlo'];
            $result_array['Status(v likvidaci)']='ANO';
            print "tututut\n";
            break;
        }
        $result_array['Nazev']=$name['Nazev'];
        $result_array['ICO']=$name['ICO'];
        $result_array['Sidlo']=$name['Sidlo'];
        $result_array['Status(v likvidaci)']='NE';
        $result_array['Pocet let na trhu']=intval(date('Y'))-intval(substr($name['Pocet let na trhu'],-4));;
        break;
    }

}
if($result_array['Status(v likvidaci)']==='NE') {
//-----------------------------------------------------------------------------------//
//Pocet zamestnancu
    unset($crawler);
    $client = new Client();
    $base = "https://wwwinfo.mfcr.cz/cgi-bin/ares/darv_res.cgi?ico=" . $result_array['ICO'] . "&jazyk=cz&xml=2";
    $crawler = $client->request("GET", trim($base));

    $pocet_zam = $crawler->filter('.BBlockBody > .BGroup:nth-child(odd)')
        ->last()->filter('.TDetailSection  tr')->last()->filter('td')->last()->text();
    $pocet_zam=trim($pocet_zam);
    if ($pocet_zam === trim('Neuvedeno')) {
        $result_array['Pocet zamestnancu'] = 0;
    } else {
        $pocet_zam = str_replace('zaměstnanců', '', $pocet_zam);
        $pocet_zam = trim($pocet_zam);
        while ($pocet_zam[0] !== '-') {
            $pocet_zam = ltrim($pocet_zam, $pocet_zam[0]);
        }
        $pocet_zam = ltrim($pocet_zam, $pocet_zam[0]);
        $pocet_zam = trim($pocet_zam);
        $result_array['Pocet zamestnancu'] = $pocet_zam;
    }


//------------------------------------------------------------------------------//
//ARES-DPH

    $base = 'https://wwwinfo.mfcr.cz/cgi-bin/ares/ares_es.cgi?jazyk=cz&obch_jm=&ico=' . $result_array['ICO'] . '&cestina=cestina&obec=&k_fu=&maxpoc=200&ulice=&cis_or=&cis_po=&setrid=ZADNE&pr_for=&nace=&xml=2&filtr=1';
    unset($crawler);
    $client = new Client();
    $crawler = $client->request("GET", $base);
    $dph_link = $crawler->filter('.TList > tr')->last()->filter('td.Tlinks1 a')->last()->link();
    $crawler = $client->click($dph_link);

    $dph = $crawler->filter('td.data')->last()->text();

    if (trim(strtolower($dph)) === 'ne') {
        $result_array['Nespolehlivy platce'] = 'NE';
    } elseif (trim(strtolower($dph)) === 'ano') {
        $result_array['Nespolehlivy platce'] = 'ANO';
    }

//-------------------------------------------------------------------//
//VR - historie
    unset($crawler);
    $client = new Client();
    $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . $result_array['ICO'] . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
    $crawler = $client->request("GET", $base);
    $uplny_vypis = $crawler->filter('#SearchResults > .section-c > .search-results > ol > li .inner > ul > li:nth-child(even) a')->link();
    $crawler = $client->click($uplny_vypis);
    $pravni_forma = $crawler->filter('.inner .aunp-content > div.vr-child > .aunp-content > .aunp-udajPanel > .div-table > .div-row > .div-cell')
        ->each(function (Crawler $node) {

            if (trim($node->text()) === 'Společnost s ručením omezeným') {
                return [
                    'Pravni forma' => 's.r.o.'
                ];
            } elseif (trim($node->text()) === 'Akciová společnost') {
                return [
                    'Pravni forma' => 'a.s.'
                ];
            } elseif (trim($node->text()) === 'Veřejná obchodní společnost') {
                return [
                    'Pravni forma' => 'v.o.s.'
                ];
            } else {
                return [];
            }
        });

    foreach ($pravni_forma as $node) {
        if (isset($node['Pravni forma'])) {
            $result_array['Pravni forma'] = $node['Pravni forma'];
        }
    }
    $zak_kap = $crawler->filter('.vr-child > .aunp-content > .aunp-udajPanel > .div-table > .div-row > .div-cell.w45mm+.div-cell > div > div:nth-child(1) > span:nth-child(2)')
        ->each(function (Crawler $node) {
            return trim($node->text());
        });
    while (strlen($zak_kap[0]) < 3) {
        array_shift($zak_kap);
    }
    if (count($zak_kap) > 1) {
        $result_array['Rust zakladniho kapitala'] = 'ANO';
    } else {
        $result_array['Rust zakladniho kapitala'] = 'NE';
    }


    unset($crawler);
    $client = new Client();
    $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . $result_array['ICO'] . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
    $crawler = $client->request("GET", $base);
    $uplny_vypis = $crawler->filter('#SearchResults > .section-c > .search-results > ol > li .inner > ul > li:nth-child(1) a')->link();
    $crawler = $client->click($uplny_vypis);
    $people = $crawler->filter('.inner .aunp-content > div.vr-child > .aunp-content > .vr-child > .aunp-content > .aunp-udajPanel > .div-table > .div-row+.div-row > .div-cell.w45mm+.div-cell > div > span > div:nth-child(1) > div:nth-child(1)')
        ->each(function (Crawler $node) {
            $name = trim($node->filter('span')->first()->text());
            $datum = trim($node->filter('span')->last()->text());
            return [
                'Name' => $name,
                'Datum' => $datum
            ];
        });
    $pocet_jednatelu = count($people);
    $result_array['Pocet jdenatelu'] = $pocet_jednatelu;

//--------------------------------------------------------------------------------------//
//Jine subjekty(people)

    function datum($date)
    {
        $men = [
            '01', '02', '03', '04', '05',
            '06', '07', '08', '09', '10',
            '11', '12'
        ];
        $mcz = [
            'ledna', 'února', 'března', 'dubna', 'května',
            'června', 'července', 'srpna', 'září', 'října',
            'listopadu', 'prosince'
        ];
        $date = str_replace('.', '', $date);
        return $date = str_replace($mcz, $men, $date);
    }

    $len = count($people);
    $i = 0;
    $zkratky = ["MUDr.", "Ing.", "Ing. arch.", "MVDr.", "MgA", "JUDr.", "PhDr.", "RNDr.", "ThLic.", "ThLic.", "PharmDr.", "Ph.D.", "Mgr."];
    while ($i < $len) {
        $man = array_shift($people);
        $full_name = $man['Name'];
        foreach ($zkratky as $zs) {
            if (strpos($full_name, $zs) !== false) {
                $full_name = str_replace($zs, '', $full_name);
            }
        }
        $full_name = trim($full_name);
        $arr = explode(' ', $full_name, 2);
        $name = $arr[0];
        $surname = $arr[1];
        $tmp = datum($man['Datum']);
        $tmp = str_replace(' ', '.', $tmp);
        $man['Name'] = iconv("UTF-8", "ASCII//TRANSLIT", $name);
        $man['Surname'] = iconv("UTF-8", "ASCII//TRANSLIT", $surname);
        $man['Datum'] = $tmp;
        array_push($people, $man);
        ++$i;
    }

    unset($crawler);
    $client = new Client();
    $pocet_jinych = 0;
    $pocet_v_lik = 0;
    foreach ($people as $man) {
        $base = 'https://www.rzp.cz/cgi-bin/aps_cacheWEB.sh?VSS_SERV=ZVWSBJFND&Action=Search&PRESVYBER=0&type=&PODLE=osoba&ICO=&OBCHJM=&ROLES=P&OKRES=&OBEC=&CASTOBCE=&ULICE=&COR=&COZ=&CDOM=&JMENO=' . $man['Name'] . '&PRIJMENI=' . $man['Surname'] . '&NAROZENI=' . $man['Datum'] . '&ROLE=&VYPIS=2';
        $crawler = $client->request("GET", $base);
        $osoba_link = $crawler->filter('#body > #obsah > .blok.data.table > table  tr.odd > td:nth-child(2) > a')->link();
        $crawler = $client->click($osoba_link);
        $pj = $crawler->filter('.podnikani + p')->text();

        for ($i = 0; $i < 3; ++$i) {
            while ($pj[0] !== ' ') {
                $pj = ltrim($pj, $pj[0]);
            }
            $pj = trim($pj);
        }
        $pocet_jinych += intval($pj) - 1;

        $pl = $crawler->filter('.blok.data.subjekt > h3')->each(function (Crawler $node) {
            return trim($node->text());
        });
        foreach ($pl as $node) {
            if (strpos($node, '"v likvidaci"') !== false) {
                $pocet_v_lik++;
            } elseif (strpos($node, 'v likvidaci') !== false) {
                $pocet_v_lik++;
            }

        }
    }
    $result_array['Pocet jinych subjektu'] = $pocet_jinych;
    $result_array['Pocet jinych subjektu v likvidaci'] = $pocet_v_lik;

//---------------------------------------------------------------//
//OZ
    $client = new Client();
    $base="https://oz.kurzy.cz/?s=";
    $firma_replace=$firma;
    $firma_replace=str_replace('s.r.o.','',$firma_replace);
    $firma_replace=str_replace('a.s.','',$firma_replace);
    $firma_replace=str_replace('v.o.s.','',$firma_replace);
    $firma_replace=trim($firma_replace);
    $firma_replace=str_replace(" ", "+",$firma_replace);
    $oz=false;
    $crawler=$client->request("GET",trim($base . $firma_replace));
    $table=$crawler->filter("table.pd.leftcolumnwidth.pad tr.ps")->each(function (Crawler $tr)
    {
        return $tr->filter("td")->each(function (Crawler $td)
        {
            return $td->text();
        });
    });
    $firma_replace=str_replace('+',' ',$firma_replace);
    foreach ($table as $row)
    {
        if(trim(strtolower(substr($row[0],0,strlen($firma_replace))))===trim(strtolower($firma_replace)) && $row[1]>0 && $row[2]>0)
        {
            $oz=true;
            break;
        }
    }
    if($oz===true)
    {
        $result_array['Oz'] = 'ANO';
    }
    else
    {
        $result_array['Oz'] = 'NE';
    }
//-------------------------------------------------------------------------------//
//Domena(whois)
    unset($crawler);
    $client =new Client();
    $base="https://rejstrik-firem.kurzy.cz/hledej/?s=".$result_array['ICO']."&r=False";
    $crawler=$client->request("GET",$base);
    if($crawler->filter('div > div+div+table  tr:nth-child(3) > td:last-child a')->count()>0)
    {
        $domena=$crawler->filter('div > div+div+table  tr:nth-child(3) > td:last-child a')->text();
        $domena=trim($domena);
        $domena=str_replace('www.','',$domena);

        $whois = new Whois();
        $query = $domena;
        $result = $whois->Lookup($query);
        function need_data($result,$tmp){
            foreach ($result as $item) {
                if(key($result)==="registered" || key($result)==="admin"|| key($result)==="domain"|| key($result)==="owner" )
                {
                    if(is_array($item)) {
                        unset($item["keyset"]);
                        unset($item["handle"]);
                        unset($item["registrar"]);
                        unset($item["nsset"]);
                        unset($item["nserver"]);
                        foreach ($item as $v) {
                            if(key($item)==="address")
                            {
                                $item["address"]=implode(" ", $item["address"]);
                            }
                            if(key($item)==="status")
                            {
                                $item["status"]=implode(",", $item["status"]);
                            }
                            next($item);
                        }
                    }
                    $tmp[key($result)]=$item;
                    next($result);
                    continue;
                }
                next($result);
                if(is_array($item))
                {
                    $tmp=need_data($item,$tmp);
                }

            }
            return $tmp;
        }
        $out=need_data($result,array());

        if ($out['registered'] === 'yes')
        {
            $result_array['Status domeny'] = 'ANO';
        }
        elseif ($out['registered'] === 'no')
        {
            $result_array['Status domeny'] = 'NE';
        }
        elseif ($out['registered'] === 'unknown')
        {
            $result_array['Status domeny'] = 'NE';
        }

    }
    else{
        $result_array['Status domeny']='NE';
    }

}
Firm::createTable();




?>