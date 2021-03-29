<?php


namespace App;


use Goutte\Client;
use Exception;
use Symfony\Component\DomCrawler\Crawler;


class Scrap
{
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

//    public function code_to_country( $code ){
//        $code = strtoupper($code);
//        $countryList = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas the', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island (Bouvetoya)', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory (Chagos Archipelago)', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros the', 'CD' => 'Congo', 'CG' => 'Congo the', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote d\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FO' => 'Faroe Islands', 'FK' => 'Falkland Islands (Malvinas)', 'FJ' => 'Fiji the Fiji Islands', 'FI' => 'Finland', 'FR' => 'France, French Republic', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia the', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and McDonald Islands', 'VA' => 'Holy See (Vatican City State)', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea', 'KR' => 'Korea', 'KW' => 'Kuwait', 'KG' => 'Kyrgyz Republic', 'LA' => 'Lao', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'AN' => 'Netherlands Antilles', 'NL' => 'Netherlands the', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn Islands', 'PL' => 'Poland', 'PT' => 'Portugal, Portuguese Republic', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia (Slovak Republic)', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia, Somali Republic', 'ZA' => 'South Africa', 'GS' => 'South Georgia and the South Sandwich Islands', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard & Jan Mayen Islands', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland, Swiss Confederation', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States of America', 'UM' => 'United States Minor Outlying Islands', 'VI' => 'United States Virgin Islands', 'UY' => 'Uruguay, Eastern Republic of', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');
//        if( !$countryList[$code] ) return $code;
//        else return $countryList[$code];
//    }
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
                $tmp = Scrap::need_data($item, $tmp);
            }

        }
        return $tmp;
    }
    //main info a status(v likvidaci?)
    public function main_info($result_array, $firma)
    {
        unset($crawler);
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
                        $result_array['Nazev'] = $name['Nazev'];
                        $result_array['ICO'] = $name['ICO'];
                        $result_array['Sidlo'] = $name['Sidlo'];
                        $result_array['Status(v likvidaci)'] = 'ANO';
                        break;
                    }
                }
                if (strlen($name['Vymazano']) > 0) {
                    continue;
                }
                if (strpos($name['Nazev'], 'v likvidaci') !== false) {
                    $result_array['Nazev'] = $name['Nazev'];
                    $result_array['ICO'] = $name['ICO'];
                    $result_array['Sidlo'] = $name['Sidlo'];
                    $result_array['Status(v likvidaci)'] = 'ANO';
                    break;
                }
                $result_array['Nazev'] = $name['Nazev'];
                $result_array['ICO'] = $name['ICO'];
                $result_array['Sidlo'] = $name['Sidlo'];
                $result_array['Status(v likvidaci)'] = 'NE';
                $result_array['Pocet let na trhu'] = intval(date('Y')) - intval(substr($name['Pocet let na trhu'], -4));;
                break;

            }
            return $result_array;
        }
        else {
            print "Spolecnost neexistuje" . "\n";
            return [];
        }
    }
    public function pocet_zamestnancu($result_array)
    {
        $client = new Client();
        $base = "https://wwwinfo.mfcr.cz/cgi-bin/ares/darv_res.cgi?ico=" . $result_array['ICO'] . "&jazyk=cz&xml=2";
        $crawler = $client->request("GET", trim($base));

        $pocet_zam = $crawler->filter('.BBlockBody > .BGroup:nth-child(odd)')
            ->last()->filter('.TDetailSection  tr')->last()->filter('td')->last()->text();
        $pocet_zam = trim($pocet_zam);
        if ($pocet_zam === trim('Neuvedeno') ||$pocet_zam === trim('Bez zaměstnanců')) {
            $result_array['Pocet zamestnancu'] = 0;
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
                $result_array['Pocet zamestnancu'] = $pocet_zam;
            }
            else
            {
                $result_array['Pocet zamestnancu'] = $pocet_zam;
            }

        }
        return $result_array;
    }
    public function dph($result_array)
    {
        $base = 'https://wwwinfo.mfcr.cz/cgi-bin/ares/ares_es.cgi?jazyk=cz&obch_jm=&ico=' . $result_array['ICO'] . '&cestina=cestina&obec=&k_fu=&maxpoc=200&ulice=&cis_or=&cis_po=&setrid=ZADNE&pr_for=&nace=&xml=2&filtr=1';
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
                    $result_array['Nespolehlivy platce'] = 'NE';
                } elseif (strpos(trim($dph),'ANO')!== false) {
                    $result_array['Nespolehlivy platce'] = 'ANO';
                } else {
                    $result_array['Nespolehlivy platce'] = null;
                }
            }
        }
        else
        {
            $result_array['Nespolehlivy platce'] = null;
        }
        return $result_array;
    }

    public function pravni_forma($result_array)
    {
        $client = new Client();
        $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . $result_array['ICO'] . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
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
                $result_array['Pravni forma'] = $node['Pravni forma'];
            }
        }
        return $result_array;
    }
    public function zakladni_kapital($result_array)
    {
        $client = new Client();
        $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . $result_array['ICO'] . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
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


        $result_array['Rust zakladniho kapitala'] = 'NE';
        foreach($zak_list as $item)
        {
            if(!empty($item))
            {
                $result_array['Rust zakladniho kapitala'] = 'ANO';
            }
        }
    return $result_array;
    }

    public function people_info($result_array)
    {
        $pocet_jinych = 0;
        $pocet_v_lik = 0;
        $pocet_jednatelu=0;
        If(!isset($result_array['Pravni forma']))
        {
            $result_array['Pravni forma']=null;
        }
        if($result_array['Pravni forma']!=='k.s.' && $result_array['Pravni forma']!=='Spolek'&& $result_array['Pravni forma']!=='Nadace') {
            unset($crawler);
            $client = new Client();
            $base = "https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . $result_array['ICO'] . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
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
                $tmp = Scrap::datum($man['Datum']);
                $tmp = str_replace(' ', '.', $tmp);
                $man['Name'] = Scrap::normalize($name);
                $man['Surname'] = Scrap::normalize($surname);
                $man['Datum'] = $tmp;
                array_push($people, $man);
                ++$i;
            }

            foreach ($people as $man)
            {
                $vek=date('Y')-date('Y',strtotime($man['Datum']));
                if($vek<=20 || $vek>=80)
                {
                    $result_array['Neduveryhodny vek jednatele'] ='ANO';
                    break;
                }
                $result_array['Neduveryhodny vek jednatele'] ='NE';
            }
            $this->people=$people;

           // print_r($people);
//            unset($crawler);
//            $client = new Client();
//            foreach ($people as $man) {
//                //print $man['Name']." ".$man['Surname']."\n";
//                $base = 'https://www.rzp.cz/cgi-bin/aps_cacheWEB.sh?VSS_SERV=ZVWSBJFND&Action=Search&PRESVYBER=0&type=&PODLE=osoba&ICO=&OBCHJM=&ROLES=P&OKRES=&OBEC=&CASTOBCE=&ULICE=&COR=&COZ=&CDOM=&JMENO=' . $man['Name'] . '&PRIJMENI=' . $man['Surname'] . '&NAROZENI=' . $man['Datum'] . '&ROLE=&VYPIS=2';
//                $crawler = $client->request("GET", $base);
//                try {
//                    $osoba_link = $crawler->filter('#body > #obsah > .blok.data.table > table  tr.odd > td:nth-child(2) > a')->link();
//                    $crawler = $client->click($osoba_link);
//                } catch (Exception $e) {
//                    continue;
//                }
//                $pj = $crawler->filter('.podnikani + p')->text();
//
//                for ($i = 0; $i < 3; ++$i) {
//                    while ($pj[0] !== ' ') {
//                        $pj = ltrim($pj, $pj[0]);
//                    }
//                    $pj = trim($pj);
//                }
//                $pocet_jinych += intval($pj) - 1;
//
//                $pl = $crawler->filter('.blok.data.subjekt > h3')->each(function (Crawler $node) {
//                    return trim($node->text());
//                });
//                foreach ($pl as $node) {
//                    if (strpos($node, '"v likvidaci"') !== false) {
//                        $pocet_v_lik++;
//                    } elseif (strpos($node, 'v likvidaci') !== false) {
//                        $pocet_v_lik++;
//                    }
//
//                }
//            }
//            unset($crawler);
//            $client = new Client();
//            foreach ($people as $man) {
//                $base = 'https://isir.justice.cz/isir/common/index.do';
//                $crawler = $client->request("GET", $base);
//                $form = $crawler->filter('.PodminkyLustrace tr td:nth-child(2) .PodminkyLustrace   tr:nth-child(3) td:nth-child(1)  #submit_button')->form();
//                $form->setValues([
//                    'nazev_osoby' => $man['Surname'],
//                    'jmeno_osoby'=>$man['Name'],
//                    'datum_narozeni'=>$man['Datum']
//                ]);
//                $crawler = $client->submit($form);
//                if($crawler->filter('.vysledekLustrace tr:nth-child(6) td:nth-child(2) b')->count()>0)
//                {
//                    $dluznik=$crawler->filter('.vysledekLustrace tr:nth-child(6) td:nth-child(2) b')->text();
//                    $dluznik=trim($dluznik);
//                    $dluznik=intval($dluznik);
//                    if($dluznik>0)
//                    {
//                        $result_array['Dluzniky']='ANO';
//                        break;
//                    }
//                    else
//                    {
//                        $result_array['Dluzniky']= 'NE';
//                    }
//                }
//
//            }
        }
//        $result_array['Pocet jednatelu'] = $pocet_jednatelu;
//        $result_array['Pocet jinych subjektu'] = $pocet_jinych;
//        $result_array['Pocet jinych subjektu v likvidaci'] = $pocet_v_lik;



        return $result_array;
    }

    public function bydleni_jednatelu($result_array)
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
                $result_array['Bydleni mimo EU']="ANO";
                return $result_array;
            }
            else
            {
                $result_array['Bydleni mimo EU']="NE";
            }
        }
        return $result_array;
    }

    public function ochranne_znamky($result_array)
    {
        $client = new Client();
        $base = "https://oz.kurzy.cz/?s=";
        $firma_replace = $result_array['Nazev'];
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
            $result_array['Oz'] = 'ANO';
        } else {
            $result_array['Oz'] = 'NE';
        }

        return $result_array;
    }

    public function domena_info($result_array)
    {
        $client = new Client();
        $base = "https://rejstrik-firem.kurzy.cz/hledej/?s=" . $result_array['ICO'] . "&r=False";
        $crawler = $client->request("GET", $base);
        if ($crawler->filter('div > div+div+table  tr:nth-child(3) > td:last-child a')->count() > 0) {
            $domena = $crawler->filter('div > div+div+table  tr:nth-child(3) > td:last-child a')->text();
            $domena = trim($domena);
            $this->dm=$domena = str_replace('www.', '', $domena);
            $result_array['Status domeny'] = 'ANO';
        } else {
            $result_array['Status domeny'] = 'NE';
        }

        return $result_array;
    }
    public function whois_parser($result,$result_array)
    {
        if(strlen($this->dm)>0)
        {
            $out = Scrap::need_data($result, array());
            if(isset($out['domain']['created']))
            {
                $result_array['Domena(let v provozu)'] = date('Y')-date('Y',strtotime($out['domain']['created']));
                return $result_array;
            }

        }
        $result_array['Domena(let v provozu)']=0;
        return $result_array;
    }

    public function pocet_provozoven($result_array)
    {
        $client = new Client();
        $base = 'https://rejstrik-firem.kurzy.cz/hledej/?s=' . $result_array['ICO'];
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
            $result_array['Pocet provozoven'] = count($tmp);
        }

        return $result_array;
    }


    public function jine_firmy_na_sidle($result_array)
    {
        $client = new Client();
        $base = 'https://rejstrik-firem.kurzy.cz/hledej/?s=' . $result_array['ICO'];
        $crawler = $client->request("GET", trim($base));
        if($crawler->filter('#orsmallinfotab  tr:nth-child(7) > td:nth-child(2) a')->count()>0)
        {
            $sidlo_link = $crawler->filter('#orsmallinfotab  tr:nth-child(7) > td:nth-child(2) a')->link();
            $crawler = $client->click($sidlo_link);
            $jine_na_sidle = trim($crawler->filter('.pd.pad.rowcl.l.colwidth  tr:nth-child(7) >td:nth-child(2)')->text());

            $string = htmlentities($jine_na_sidle, null, 'utf-8');
            $content = str_replace("&nbsp;", "", $string);
            $content = html_entity_decode($content);

            $result_array['Jine subjekty na sidle'] = $content;
        }

        return $result_array;
    }

    public function nabidky_prace($result_array)
    {
        $client = new Client();
        $base = $base = 'https://rejstrik-firem.kurzy.cz/hledej/?s=' . $result_array['ICO'];
        $crawler = $client->request("GET", trim($base));
        if($crawler->filter('.leftcolumnwidth.ecb > .topmenuxslide > .tabs2 li:nth-child(6) a')->count()>0)
        {
            $nabidka_link = $crawler->filter('.leftcolumnwidth.ecb > .topmenuxslide > .tabs2 li:nth-child(6) a')->link();
            $crawler = $client->click($nabidka_link);
            $nabidka_str = $crawler->filter('.leftcolumnwidth.ecb h2:nth-child(7)')->text();
            $nabidka_str = trim($nabidka_str);
            if (strpos($nabidka_str, 'celkem') !== false) {
                $result_array['Volne nabidky prace'] = 'ANO';
            } else {
                $result_array['Volne nabidky prace'] = 'NE';
            }
        }

        return $result_array;
    }

}


