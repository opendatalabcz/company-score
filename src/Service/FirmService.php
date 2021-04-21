<?php


namespace App\Service;

use App\Entity\Firm;
use App\Repository\FirmRepository;
use Exception;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;


class FirmService
{
    private $result_array = [];
    private $dm='';
    private $people=[];

    /**
     * @return mixed
     */
    public function getDm()
    {
        return $this->dm;
    }

    /**
     * @return array
     */
    public function getPeople(): array
    {
        return $this->people;
    }

    /**
     * @return array
     */
    public function getResultArray(): array
    {
        return $this->result_array;
    }

    /**
     * @param array $result_array
     */
    public function setResultArray(array $result_array): void
    {
        $this->result_array = $result_array;
    }

    public function datum($date)
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

    public function normalize($string)
    {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ě' => 'e',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', 'Ř' => 'R', 'ř' => 'r', 'Ď' => 'D', 'ď' => 'd', 'Ň' => 'N', 'ň' => 'n', 'Ě' => 'E'
        );

        return strtr($string, $table);
    }

    public function need_data($result, $tmp)
    {
        foreach ($result as $item) {
            if (key($result) === "registered" || key($result) === "admin" || key($result) === "domain" || key($result) === "owner") {
                if (is_array($item)) {
                    unset($item["keyset"]);
                    unset($item["handle"]);
                    unset($item["registrar"]);
                    unset($item["nsset"]);
                    unset($item["nserver"]);
                    if (isset($item["address"])) {
                        $item["address"] = implode(" ", (array) $item["address"]);
                    }
                    if (isset($item["status"])) {
                        $item["status"] = implode(",", (array) $item["status"]);
                    }
                }
                $tmp[key($result)] = $item;
                next($result);
                continue;
            }
            next($result);
            if (is_array($item)) {
                $tmp = FirmService::need_data($item, $tmp);
            }

        }
        return $tmp;
    }
    public function main_info($firma)
    {
        $client = new Client();
        $base = 'https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY';
        $crawler = $client->request("GET", trim($base));
        $form = $crawler->filter('.search-buttons button.button')->form();
        $firma = trim($firma);
        $form->setValues([
            'ico' => $firma
        ]);
        $crawler = $client->submit($form);
        if ($crawler->filter('#SearchResults > .section-c > .search-results > ol > li')->count() > 0) {
            $all_names = $crawler->filter('#SearchResults > .section-c > .search-results > ol > li')->each(function (Crawler $li) {
                $ico = trim($li->filter('.inner > .result-details > tbody tr')->first()->filter('td')->last()->text());
                $name = trim($li->filter('.inner > .result-details > tbody tr:nth-child(1) > th+td .left')->text());
                $sidlo = trim($li->filter('.inner > .result-details > tbody tr ')->last()->filter('td')->text());
                $pocet_let = trim($li->filter('.inner > .result-details > tbody tr:nth-child(even)')->first()->filter("td")->last()->text());
                $vymazano = '';
                if ($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->count() > 0) {
                    $vymazano = trim($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->text());
                }
                return [
                    'Nazev' => $name,
                    'ICO' => $ico,
                    'Sidlo' => $sidlo,
                    'Pocet let na trhu' => $pocet_let,
                    'Vymazano' => $vymazano
                ];
            });

            foreach ($all_names as $name) {
                if (count($all_names) === 1) {
                    if (strlen($name['Vymazano']) > 0) {
                        $this->result_array['Nazev'] = $name['Nazev'];
                        $this->result_array['ICO'] = $name['ICO'];
                        $this->result_array['Sidlo'] = $name['Sidlo'];
                        $this->result_array['Status(v likvidaci)'] = 'ANO';
                        break;
                    }
                }
                if (strlen($name['Vymazano']) > 0) {
                    continue;
                }
                if (strpos($name['Nazev'], 'v likvidaci') !== false) {
                    $this->result_array['Nazev'] = $name['Nazev'];
                    $this->result_array['ICO'] = $name['ICO'];
                    $this->result_array['Sidlo'] = $name['Sidlo'];
                    $this->result_array['Status(v likvidaci)'] = 'ANO';
                    break;
                }
                $this->result_array['Nazev'] = $name['Nazev'];
                $this->result_array['ICO'] = $name['ICO'];
                $this->result_array['Sidlo'] = $name['Sidlo'];
                $this->result_array['Status(v likvidaci)'] = 'NE';
                $this->result_array['Pocet let na trhu'] = intval(date('Y')) - intval(substr($name['Pocet let na trhu'], -4));;
                break;

            }
            return true;
        } else {
            return false;
        }
    }

    public function createFirm($ico): ?Firm
    {
        if ($this->main_info($ico)) {
            $firm = new Firm();
            $firm->setIco($this->result_array['ICO']);
            $firm->setName($this->result_array['Nazev']);
            $firm->setSidlo($this->result_array['Sidlo']);
            return $firm;
        }
        return null;
    }

    public function pravni_forma($ico)
    {
        $client = new Client();
        $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . trim($ico) . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
        $crawler = $client->request("GET", $base);
        $all_names = $crawler->filter('#SearchResults > .section-c > .search-results > ol > li')->each(function (Crawler $li) {
            $lnk = $li->filter('.inner > ul > li:nth-child(1) a')->link();
            $vymazano = '';
            if ($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->count() > 0) {
                $vymazano = trim($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->text());
            }
            return [
                'Vymazano' => $vymazano,
                'Link' => $lnk
            ];
        });
        foreach ($all_names as $link) {
            if (strlen($link['Vymazano']) > 0) {
                continue;
            }
            $crawler = $client->click($link['Link']);
        }
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
                }elseif (trim($node->text()) === 'Komanditní společnost') {
                    return [
                        'Pravni forma' => 'k.s.'
                    ];
                }elseif (trim($node->text()) === 'Spolek') {
                    return [
                        'Pravni forma' => 'Spolek'
                    ];
                }elseif (trim($node->text()) === 'Pobočný spolek') {
                    return [
                        'Pravni forma' => 'Spolek'
                    ];
                }
                elseif (trim($node->text()) === 'Fyzická osoba - podnikatel') {
                    return [
                        'Pravni forma' => 'podnikatel'
                    ];
                }
                elseif (trim($node->text()) === 'Nadace') {
                    return [
                        'Pravni forma' => 'Nadace'
                    ];
                }else {
                    return [];
                }
            });

        foreach ($pravni_forma as $node) {
            if (isset($node['Pravni forma'])) {
                $this->result_array['Pravni forma'] = $node['Pravni forma'];
            }
        }
    }
    public function people_info($ico)
    {
        $pocet_jinych = 0;
        $pocet_v_lik = 0;
        $pocet_jednatelu=0;
        If(!isset($this->result_array['Pravni forma']))
        {
            $this->result_array['Pravni forma']=null;
        }
        if($this->result_array['Pravni forma']!=='k.s.' && $this->result_array['Pravni forma']!=='Spolek'&& $this->result_array['Pravni forma']!=='Nadace') {
            unset($crawler);
            $client = new Client();
            $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . trim($ico) . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
            $crawler = $client->request("GET", $base);
            unset($all_names);
            $all_names = $crawler->filter('#SearchResults > .section-c > .search-results > ol > li')->each(function (Crawler $li) {
                $lnk = $li->filter('.inner > ul > li:nth-child(1) a')->link();
                $vymazano = '';
                if ($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->count() > 0) {
                    $vymazano = trim($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->text());
                }
                return [
                    'Vymazano' => $vymazano,
                    'Link' => $lnk
                ];
            });
            foreach ($all_names as $link) {
                if (strlen($link['Vymazano']) > 0) {
                    continue;
                }
                $crawler = $client->click($link['Link']);
            }
            $people = $crawler->filter('.inner .aunp-content > div.vr-child > .aunp-content > .vr-child > .aunp-content > .aunp-udajPanel > .div-table > .div-row > .div-cell.w45mm+.div-cell > div > span > div:nth-child(1)')
                ->each(function (Crawler $node) {
                    if($node->filter('div:nth-child(1) > span:nth-child(1)')->count()>0 &&$node->filter('div:nth-child(1) > span')->last()->count()>0 &&$node->filter('div:nth-child(2) > span')->count()>0)
                    {
                        $name = trim($node->filter('div:nth-child(1) > span:nth-child(1)')->text());
                        $datum = trim($node->filter('div:nth-child(1) > span')->last()->text());
                        $bydleni = trim($node->filter('div:nth-child(2) > span')->text());
                        return [
                            'Name' => $name,
                            'Datum' => $datum,
                            'Bydleni' => $bydleni
                        ];
                    }
                    else {
                        return [];
                    }

                });

            $people1=array();
            foreach($people as $man)
            {
                if(!empty($man))
                {
                    array_push($people1,$man);
                }
            }
            unset($people);
            $people=$people1;

            $pocet_jednatelu = count($people);
            $len = count($people);
            $i = 0;
            $zkratky = ["Dipl.", "Bc.", "MUDr.", "Ing.", "Ing. arch.", "MVDr.", "MgA", "JUDr.", "PhDr.", "RNDr.", "ThLic.", "ThLic.", "PharmDr.", "Ph.D.", "Mgr."];
            while ($i < $len) {
                $man = array_shift($people);
                $full_name = $man['Name'];
                $full_name = str_replace('/', '', $full_name);
                $full_name = preg_replace('/[0-9]+/', '', $full_name);
                foreach ($zkratky as $zs) {
                    if (strpos($full_name, $zs) !== false || strpos($full_name, strtolower($zs)) !== false) {
                        $full_name = str_replace($zs, '', $full_name);
                        $full_name = str_replace(strtolower($zs), '', $full_name);
                    }
                }
                $full_name = trim($full_name);
                $arr = explode(' ', $full_name);
                $surname = array_pop($arr);
                $name = '';
                foreach ($arr as $str) {
                    $name = $name . $str . " ";
                }
                $name = trim($name);
                $tmp = FirmService::datum($man['Datum']);
                $tmp = str_replace(' ', '.', $tmp);
                $man['Name'] = FirmService::normalize($name);
                $man['Surname'] = FirmService::normalize($surname);
                $man['Datum'] = $tmp;
                array_push($people, $man);
                ++$i;
            }

            foreach ($people as $man)
            {
                $vek=date('Y')-date('Y',strtotime($man['Datum']));
                if($vek<=20 || $vek>=80)
                {
                    $this->result_array['Neduveryhodny vek jednatele'] ='ANO';
                    break;
                }
                $this->result_array['Neduveryhodny vek jednatele'] ='NE';
            }
            $this->people=$people;

            // print_r($people);
            unset($crawler);
            $client = new Client();
            foreach ($this->people as $man) {
                //print $man['Name']." ".$man['Surname']."\n";
                $base = 'https://www.rzp.cz/cgi-bin/aps_cacheWEB.sh?VSS_SERV=ZVWSBJFND&Action=Search&PRESVYBER=0&type=&PODLE=osoba&ICO=&OBCHJM=&ROLES=P&OKRES=&OBEC=&CASTOBCE=&ULICE=&COR=&COZ=&CDOM=&JMENO=' . $man['Name'] . '&PRIJMENI=' . $man['Surname'] . '&NAROZENI=' . $man['Datum'] . '&ROLE=&VYPIS=2';
                $crawler = $client->request("GET", $base);
                try {
                    $osoba_link = $crawler->filter('#body > #obsah > .blok.data.table > table  tr.odd > td:nth-child(2) > a')->link();
                    $crawler = $client->click($osoba_link);
                } catch (Exception $e) {
                    continue;
                }
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
            unset($crawler);
            $client = new Client();
            foreach ($this->people as $man) {
                $base = 'https://isir.justice.cz/isir/common/index.do';
                $crawler = $client->request("GET", $base);
                $form = $crawler->filter('.PodminkyLustrace tr td:nth-child(2) .PodminkyLustrace   tr:nth-child(3) td:nth-child(1)  #submit_button')->form();
                $form->setValues([
                    'nazev_osoby' => $man['Surname'],
                    'jmeno_osoby'=>$man['Name'],
                    'datum_narozeni'=>$man['Datum']
                ]);
                $crawler = $client->submit($form);
                if($crawler->filter('.vysledekLustrace tr:nth-child(6) td:nth-child(2) b')->count()>0)
                {
                    $dluznik=$crawler->filter('.vysledekLustrace tr:nth-child(6) td:nth-child(2) b')->text();
                    $dluznik=trim($dluznik);
                    $dluznik=intval($dluznik);
                    if($dluznik>0)
                    {
                        $this->result_array['Dluzniky']='ANO';
                        break;
                    }
                    else
                    {
                        $this->result_array['Dluzniky']= 'NE';
                    }
                }
            }
        }
        if(empty($this->people))
        {
            $this->result_array['Neduveryhodny vek jednatele'] =null;
            $this->result_array['Dluzniky']=null;
        }
        $this->result_array['Pocet jednatelu'] = $pocet_jednatelu;
        $this->result_array['Pocet jinych subjektu'] = $pocet_jinych;
        $this->result_array['Pocet jinych subjektu v likvidaci'] = $pocet_v_lik;
    }
    public function bydleni_jednatelu()
    {
        foreach ($this->people as $man)
        {
            $p=explode(',',$man['Bydleni']);
            $first=array_pop($p);
            $first=trim($first);
            if(strpos(trim($first),'PSČ')!==false)
            {
                $p=array_reverse($p);
                $first=array_pop($p);
                $first=trim($first);
            }
            $search_url = "https://nominatim.openstreetmap.org/search?q=".str_replace(" ","+",$first)."&format=json";
            $httpOptions = [
                "http" => [
                    "method" => "GET",
                    "header" => "User-Agent: bk",
                    "email"=>   "belyikir@cvut.cz"
                ]
            ];
            $streamContext = stream_context_create($httpOptions);
            $json = file_get_contents($search_url, false, $streamContext);
            $decoded = json_decode($json, true);
            if(empty($decoded))
                continue;
            $adr=$decoded[0];
            $country_name=$adr['display_name'];
            $pieces = explode(',', $country_name);
            $last_word=array_pop($pieces);

            if(strpos($last_word,'/')!==false)
            {
                $p=explode('/',$last_word);
                $last_word=array_pop($p);
            }
            $last_word=trim($last_word);
            $real='';
            $base="https://en.wikipedia.org/wiki/List_of_alternative_country_names";
            $client=new Client();
            $crawler=$client->request("GET",$base);
            $all=$crawler->filter('.wikitable tr')->each(function (Crawler $node) use ($last_word) {
                if($node->filter("td:nth-child(3)>b")->count()>0)
                {
                    $text=$node->filter("td:nth-child(3) > b ")->each(function (Crawler $node) use ($last_word) {
                        return trim($node->text());
                    });
                    foreach ($text as $t)
                    {
                        if(strpos(trim($t),$last_word)!==false)
                        {
                            return trim($node->filter("td:nth-child(2) > a")->text());
                        }
                    }
                }
                return [];
            });
            foreach ($all as $name)
            {
                if(!empty($name))
                {
                    $real=$name;
                }

            }
            $eu_countries = array('HR' => 'Croatia',"CA" => "Canada", "US" => "United States", "CH"=>"Switzerland","AT" => "Austria", "BE" => "Belgium", "BG" => "Bulgaria", "CY" => "Cyprus", "CZ" => "Czech Republic", "DK" => "Denmark", "EE" => "Estonia", "FI" => "Finland", "FR" => "France", "DE" => "Germany", "GR" => "Greece", "HU" => "Hungary", "IE" => "Ireland", "IT" => "Italy", "LV" => "Latvia", "LT" => "Lithuania", "LU" => "Luxembourg", "MT" => "Malta", "NL" => "Netherlands", "PL" => "Poland", "PT" => "Portugal", "RO" => "Romania", "SK" => "Slovakia (Slovak Republic)", "SI" => "Slovenia", "ES" => "Spain", "SE" => "Sweden", "GB" => "United Kingdom");
            if(!array_search($real,$eu_countries))
            {
                $this->result_array['Bydleni mimo EU']="ANO";
                return;
            }
            else
            {
                $this->result_array['Bydleni mimo EU']="NE";
            }
        }
        if(empty($this->people))
            $this->result_array['Bydleni mimo EU']=null;
    }
    public function pocet_zamestnancu($ico)
    {
        $client = new Client();
        $base = "https://wwwinfo.mfcr.cz/cgi-bin/ares/darv_res.cgi?ico=" . trim($ico) . "&jazyk=cz&xml=2";
        $crawler = $client->request("GET", trim($base));

        $pocet_zam = $crawler->filter('.BBlockBody > .BGroup:nth-child(odd)')
            ->last()->filter('.TDetailSection  tr')->last()->filter('td')->last()->text();
        $pocet_zam = trim($pocet_zam);
        if ($pocet_zam === trim('Neuvedeno') ||$pocet_zam === trim('Bez zaměstnanců')) {
            $this->result_array['Pocet zamestnancu'] = 0;
        } else {
            $pocet_zam = str_replace('zaměstnanců', '', $pocet_zam);
            $pocet_zam = str_replace('zaměstnanci', '', $pocet_zam);
            $pocet_zam = str_replace('a více zam.', '', $pocet_zam);
            $pocet_zam = str_replace(' ', '', $pocet_zam);
            $pocet_zam = trim($pocet_zam);
            if(strpos($pocet_zam,'-')!==false)
            {
                while ($pocet_zam[0] !== '-') {
                    $pocet_zam = ltrim($pocet_zam, $pocet_zam[0]);
                }
                $pocet_zam = ltrim($pocet_zam, $pocet_zam[0]);
                $pocet_zam = trim($pocet_zam);
                $this->result_array['Pocet zamestnancu'] = $pocet_zam;
            }
            else
            {
                $this->result_array['Pocet zamestnancu'] = $pocet_zam;
            }

        }
    }
    public function dph($ico)
    {
        $base = 'https://wwwinfo.mfcr.cz/cgi-bin/ares/ares_es.cgi?jazyk=cz&obch_jm=&ico=' . trim($ico) . '&cestina=cestina&obec=&k_fu=&maxpoc=200&ulice=&cis_or=&cis_po=&setrid=ZADNE&pr_for=&nace=&xml=2&filtr=1';
        unset($crawler);
        $client = new Client();
        $crawler = $client->request("GET", $base);
        if($crawler->filter('.TList tr:last-child > .Tlinks1 a[title="Údaje z registru plátců DPH"]')->count()>0)
        {
            $dph_link = $crawler->filter('.TList tr:last-child > .Tlinks1 a[title="Údaje z registru plátců DPH"]')->link();
            $crawler = $client->click($dph_link);
            if($crawler->filter('td.data')->last()->count() >0)
            {
                $dph = $crawler->filter('td.data')->last()->text();

                if (strpos(trim($dph),'NE')!== false) {
                    $this->result_array['Nespolehlivy platce'] = 'NE';
                } elseif (strpos(trim($dph),'ANO')!== false) {
                    $this->result_array['Nespolehlivy platce'] = 'ANO';
                } else {
                    $this->result_array['Nespolehlivy platce'] = null;
                }
            }
        }
        else
        {
            $this->result_array['Nespolehlivy platce'] = null;
        }
    }

    public function jine_firmy_na_sidle($ico)
    {
        $client = new Client();
        $base = 'https://rejstrik-firem.kurzy.cz/hledej/?s=' . trim($ico);
        $crawler = $client->request("GET", trim($base));
        if($crawler->filter('#orsmallinfotab  tr:nth-child(7) > td:nth-child(2) a')->count()>0)
        {
            $sidlo_link = $crawler->filter('#orsmallinfotab  tr:nth-child(7) > td:nth-child(2) a')->link();
            $crawler = $client->click($sidlo_link);
            $jine_na_sidle = trim($crawler->filter('.pd.pad.rowcl.l.colwidth  tr:nth-child(7) >td:nth-child(2)')->text());

            $string = htmlentities($jine_na_sidle, null, 'utf-8');
            $content = str_replace("&nbsp;", "", $string);
            $content = html_entity_decode($content);

            $this->result_array['Jine subjekty na sidle'] = $content;
        }
    }

    public function domena_info($ico)
    {
        $client = new Client();
        $base = "https://rejstrik-firem.kurzy.cz/hledej/?s=" . trim($ico) . "&r=False";
        $crawler = $client->request("GET", $base);
        if ($crawler->filter('div > div+div+table  tr:nth-child(3) > td:last-child a')->count() > 0) {
            $domena = $crawler->filter('div > div+div+table  tr:nth-child(3) > td:last-child a')->text();
            $domena = trim($domena);
            $this->dm=str_replace('www.', '', $domena);
            $this->result_array['Status domeny'] = 'ANO';
        } else {
            $this->result_array['Status domeny'] = 'NE';
        }

    }
    public function whois_parser($result)
    {
        if(strlen($this->dm)>0)
        {
            $out = FirmService::need_data($result, array());
            if(isset($out['domain']['created']))
            {
                $this->result_array['Domena(let v provozu)'] = date('Y')-date('Y',strtotime($out['domain']['created']));
                return;
            }
        }
        $this->result_array['Domena(let v provozu)']=0;

    }
    public function whois_parser2($result)
    {
        if(strlen($this->dm)>0)
        {
            $out = FirmService::need_data($result, array());
            if(isset($out['domain']['changed']))
            {
                $this->result_array['Domena(posledni modifikace)'] = date('Y')-date('Y',strtotime($out['domain']['changed']));
                return;
            }

        }
        $this->result_array['Domena(posledni modifikace)']=100;

    }

    public function nabidky_prace($ico)
    {
        $client = new Client();
        $base = $base = 'https://rejstrik-firem.kurzy.cz/hledej/?s=' . trim($ico);
        $crawler = $client->request("GET", trim($base));
        if($crawler->filter('.leftcolumnwidth.ecb > .topmenuxslide > .tabs2 li:nth-child(6) a')->count()>0)
        {
            $nabidka_link = $crawler->filter('.leftcolumnwidth.ecb > .topmenuxslide > .tabs2 li:nth-child(6) a')->link();
            $crawler = $client->click($nabidka_link);
            $nabidka_str = $crawler->filter('.leftcolumnwidth.ecb h2:nth-child(7)')->text();
            $nabidka_str = trim($nabidka_str);
            if (strpos($nabidka_str, 'celkem') !== false) {
                $this->result_array['Volne nabidky prace'] = 'ANO';
            } else {
                $this->result_array['Volne nabidky prace'] = 'NE';
            }
        }
    }

    public function pocet_provozoven($ico)
    {
        $client = new Client();
        $base = 'https://rejstrik-firem.kurzy.cz/hledej/?s=' . trim($ico);
        $crawler = $client->request("GET", trim($base));
        if($crawler->filter('#main > #leftcolumn > .leftcolumnwidth.ecb> .topmenuxslide > #ormenu2 > li:nth-child(2) a')->count()>0)
        {
            $zivno_link = $crawler->filter('#main > #leftcolumn > .leftcolumnwidth.ecb> .topmenuxslide > #ormenu2 > li:nth-child(2) a')->link();
            $crawler = $client->click($zivno_link);
            $provozovny = $crawler->filter('#ormaininfotab > .ecb > #provozovny > div > div')->each(function (Crawler $node) {
                return trim($node->text());
            });
            $provozovny1 = array();
            $tmp = array();
            foreach ($provozovny as $item) {
                $item = trim($item);
                preg_match('/\d{10}/', $item, $provozovny1);
                foreach ($provozovny1 as $pr)
                    array_push($tmp, $pr);
            }
            $tmp = array_unique($tmp);
            $this->result_array['Pocet provozoven'] = count($tmp);
        }
    }

    public function zakladni_kapital($ico)
    {
        $client = new Client();
        $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . trim($ico) . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
        $crawler = $client->request("GET", $base);
        $all_names = $crawler->filter('#SearchResults > .section-c > .search-results > ol > li')->each(function (Crawler $li) {
            $lnk = $li->filter('.inner > ul > li:nth-child(2) a')->link();
            $vymazano = '';
            if ($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->count() > 0) {
                $vymazano = trim($li->filter('.inner > .result-details > tbody > tr:nth-child(1) > th.nowrap+td > .subject-state.vymazano')->text());
            }
            return [
                'Vymazano' => $vymazano,
                'Link' => $lnk
            ];
        });
        foreach ($all_names as $link) {
            if (strlen($link['Vymazano']) > 0) {
                continue;
            }
            $crawler = $client->click($link['Link']);
        }

        $zak_list=$crawler->filter('.vr-child > .aunp-content > .aunp-udajPanel > .vr-hlavicka+.div-table > .div-row')
            ->each(function (Crawler $node)
            {
                if($node->filter('.div-cell.w45mm > .vr-hlavicka > .nounderline')->count()>0)
                {
                    if(trim($node->filter('.div-cell.w45mm > .vr-hlavicka > .nounderline')->text())==='Základní kapitál:')
                    {
                        if ($node->filter('.div-cell > div.underline > div:nth-child(1) > span:nth-child(2)')->count() > 0)
                        {
                            return [
                                'Rust'=>'ANO'
                            ];
                        }
                    }
                }
                return [];
            });
        $this->result_array['Rust zakladniho kapitala'] = 'NE';
        foreach($zak_list as $item)
        {
            if(!empty($item))
            {
                $this->result_array['Rust zakladniho kapitala'] = 'ANO';
            }
        }
    }
    public function ochranne_znamky($nazev)
    {
        $client = new Client();
        $base = "https://oz.kurzy.cz/?s=";
        $firma_replace = $nazev;
        $firma_replace = str_replace('s.r.o.', '', $firma_replace);
        $firma_replace = str_replace('a.s.', '', $firma_replace);
        $firma_replace = str_replace('a. s.', '', $firma_replace);
        $firma_replace = str_replace('v.o.s.', '', $firma_replace);
        $firma_replace = str_replace('z.s.', '', $firma_replace);
        $firma_replace = str_replace(',', '', $firma_replace);
        $firma_replace = trim($firma_replace);
        $firma_replace = str_replace(" ", "+", $firma_replace);
        $oz = false;
        $crawler = $client->request("GET", trim($base . $firma_replace));
        if($crawler->filter("table.pd.leftcolumnwidth.pad tr.ps")->count()>0)
        {
            $table = $crawler->filter("table.pd.leftcolumnwidth.pad tr.ps")->each(function (Crawler $tr) {
                return $tr->filter("td")->each(function (Crawler $td) {
                    return $td->text();
                });
            });
            $firma_replace = str_replace('+', ' ', $firma_replace);
            foreach ($table as $row) {
                if (trim(strtolower(substr($row[0], 0, strlen($firma_replace)))) === trim(strtolower($firma_replace)) && $row[1] > 0 && $row[2] > 0) {
                    $oz = true;
                    break;
                }
            }
        }
        else
        {
            $table=$crawler->filter(".pd.leftcolumnwidth.rowcl.padall tr.ps td.owner")->each(function (Crawler  $node)
            {
                return trim($node->text());
            });
            $firma_replace = str_replace('+', ' ', $firma_replace);
            foreach ($table as $item)
            {
                if(strtolower($firma_replace)===strtolower($item))
                {
                    $oz=true;
                    break;
                }
            }
        }
        if ($oz === true) {
            $this->result_array['Oz'] = 'ANO';
        } else {
            $this->result_array['Oz'] = 'NE';
        }

    }


}