<?php

namespace App;


class Firm
{
    protected $id;
    protected $nazev;
    protected $ICO;
    protected $sidlo;
    protected $status;
    protected $pocet_let_na_trhu;
    protected $pocet_zamestnancu;
    protected $pravni_forma;
    protected $nespolehlivy_platce;
    protected $rust_zakladniho_kapitala;
    protected $pocet_jednatelu;
    protected $neduveryhodny_vek_jednatele;
    protected $bydleni_mimo_eu;
    protected $pocet_jinych_subjektu;
    protected $pocet_jinych_v_likvidaci;
    protected $dluzniky;
    protected $oz;
    protected $status_domeny;
    protected $domena_let_v_provozu;
    protected $pocet_provozoven;
    protected $jine_subjekty_na_sidle;
    protected $volne_nabidky_prace;
    protected $test_zakldani;
    protected $test_jednatelu;
    protected $test_samotneho_subjekta;
    protected $test_domeny;
    protected $test_bonusovy;
    protected $vysledek;

    /**
     * Firm constructor.
     * @param $nazev
     * @param $ICO
     * @param $sidlo
     * @param $status
     * @param $pocet_let_na_trhu
     * @param $pocet_zamestnancu
     * @param $pravni_forma
     * @param $nespolehlivy_platce
     * @param $rust_zakladniho_kapitala
     * @param $pocet_jednatelu
     * @param $neduveryhodny_vek_jednatele
     * @param $bydleni_mimo_eu
     * @param $pocet_jinych_subjektu
     * @param $pocet_jinych_v_likvidaci
     * @param $dluzniky
     * @param $oz
     * @param $status_domeny
     * @param $domena_let_v_provozu
     * @param $pocet_provozoven
     * @param $jine_subjekty_na_sidle
     * @param $volne_nabidky_prace
     * @param $test_zakldani
     * @param $test_jednatelu
     * @param $test_samotneho_subjekta
     * @param $test_domeny
     * @param $test_bonusovy
     * @param $vysledek
     */
    public function __construct($nazev, $ICO, $sidlo, $status, $pocet_let_na_trhu, $pocet_zamestnancu, $pravni_forma, $nespolehlivy_platce, $rust_zakladniho_kapitala, $pocet_jednatelu, $neduveryhodny_vek_jednatele, $bydleni_mimo_eu, $pocet_jinych_subjektu, $pocet_jinych_v_likvidaci, $dluzniky, $oz, $status_domeny, $domena_let_v_provozu, $pocet_provozoven, $jine_subjekty_na_sidle, $volne_nabidky_prace, $test_zakldani, $test_jednatelu, $test_samotneho_subjekta, $test_domeny, $test_bonusovy, $vysledek)
    {
        $this->nazev = $nazev;
        $this->ICO = $ICO;
        $this->sidlo = $sidlo;
        $this->status = $status;
        $this->pocet_let_na_trhu = $pocet_let_na_trhu;
        $this->pocet_zamestnancu = $pocet_zamestnancu;
        $this->pravni_forma = $pravni_forma;
        $this->nespolehlivy_platce = $nespolehlivy_platce;
        $this->rust_zakladniho_kapitala = $rust_zakladniho_kapitala;
        $this->pocet_jednatelu = $pocet_jednatelu;
        $this->neduveryhodny_vek_jednatele = $neduveryhodny_vek_jednatele;
        $this->bydleni_mimo_eu = $bydleni_mimo_eu;
        $this->pocet_jinych_subjektu = $pocet_jinych_subjektu;
        $this->pocet_jinych_v_likvidaci = $pocet_jinych_v_likvidaci;
        $this->dluzniky = $dluzniky;
        $this->oz = $oz;
        $this->status_domeny = $status_domeny;
        $this->domena_let_v_provozu = $domena_let_v_provozu;
        $this->pocet_provozoven = $pocet_provozoven;
        $this->jine_subjekty_na_sidle = $jine_subjekty_na_sidle;
        $this->volne_nabidky_prace = $volne_nabidky_prace;
        $this->test_zakldani = $test_zakldani;
        $this->test_jednatelu = $test_jednatelu;
        $this->test_samotneho_subjekta = $test_samotneho_subjekta;
        $this->test_domeny = $test_domeny;
        $this->test_bonusovy = $test_bonusovy;
        $this->vysledek = $vysledek;
    }


    public static function createTable(): void
    {
        $db = Db::get();
        $db->query('CREATE TABLE IF NOT EXISTS firm (
            firm_id INTEGER PRIMARY KEY,
            nazev INTEGER,
            ico TEXT,
            sidlo TEXT,
            status TEXT,
            pocet_let_na_trhu INTEGER,
            pocet_zamestnancu INTEGER,
            pravni_forma TEXT,
            nespolehlivy_platce TEXT,
            rust_zakladniho_kapitala TEXT,
            pocet_jednatelu INTEGER,
            neduveryhodny_vek_jednatele TEXT,
            bydleni_mimo_eu TEXT,
            pocet_jinych_subjektu INTEGER,
            pocet_jinych_v_likvidaci INTEGER,
            insolvence TEXT,
            oz TEXT,
            status_domeny TEXT,
            domena_let_v_provozu INTEGER ,
            pocet_provozoven INTEGER ,
            jine_subjekty_na_sidle INTEGER ,
            aktualni_nabidky_prace TEXT,
            test_zakladni INTEGER ,
            test_jednatelu INTEGER,
            test_samotneho_subjekta INTEGER ,
            test_domeny INTEGER,
            test_bonusovy INTEGER,
            vysledek INTEGER 
        )');
    }
        public static function dropTable(): void
    {
        $db=Db::get();
        $db->query('DROP TABLE IF EXISTS firm ');
    }
        public static function insertion($nazev, $ICO, $sidlo, $status, $pocet_let_na_trhu, $pocet_zamestnancu, $pravni_forma, $nespolehlivy_platce, $rust_zakladniho_kapitala, $pocet_jednatelu, $neduveryhodny_vek_jednatele, $bydleni_mimo_eu, $pocet_jinych_subjektu, $pocet_jinych_v_likvidaci, $dluzniky, $oz, $status_domeny, $domena_let_v_provozu, $pocet_provozoven, $jine_subjekty_na_sidle, $volne_nabidky_prace, $test_zakldani, $test_jednatelu, $test_samotneho_subjekta, $test_domeny, $test_bonusovy, $vysledek):void
        {
            $db=Db::get();
            $s=$db->prepare('SELECT * FROM `firm` WHERE ico=:ico');
            $s->execute([
                'ico' => $ICO
            ]);
            $array=$s->fetch(PDO::FETCH_ASSOC);
            if($array)
            {
                return;
            }
            $s=$db->prepare('INSERT INTO firm(nazev,ico,sidlo,status,pocet_let_na_trhu,pocet_zamestnancu,pravni_forma,nespolehlivy_platce,
            rust_zakladniho_kapitala,pocet_jednatelu,neduveryhodny_vek_jednatele, bydleni_mimo_eu,pocet_jinych_subjektu,pocet_jinych_v_likvidaci,insolvence,oz,status_domeny,domena_let_v_provozu,pocet_provozoven,
                 jine_subjekty_na_sidle,aktualni_nabidky_prace,test_zakladni,test_jednatelu,test_samotneho_subjekta,test_domeny,test_bonusovy,vysledek)
            VALUES (:nazev,:ico,:sidlo,:status,:pocet_let_na_trhu,:pocet_zamestnancu,:pravni_forma,:nespolehlivy_platce,
            :rust_zakladniho_kapitala,:pocet_jednatelu,:neduveryhodny_vek_jednatele, :bydleni_mimo_eu,:pocet_jinych_subjektu,:pocet_jinych_v_likvidaci,:insolvence,:oz,:status_domeny,:domena_let_v_provozu,:pocet_provozoven,
                 :jine_subjekty_na_sidle,:aktualni_nabidky_prace,:test_zakladni,:test_jednatelu,:test_samotneho_subjekta,:test_domeny,:test_bonusovy,:vysledek)');
            $s->execute([
                'nazev'=>$nazev,
                'ico'=>$ICO,
                'sidlo'=>$sidlo,
                'status'=>$status,
                'pocet_let_na_trhu'=>$pocet_let_na_trhu,
                'pocet_zamestnancu'=>$pocet_zamestnancu,
                'pravni_forma'=>$pravni_forma,
                'nespolehlivy_platce'=>$nespolehlivy_platce,
                'rust_zakladniho_kapitala'=>$rust_zakladniho_kapitala,
                'pocet_jednatelu'=>$pocet_jednatelu,
                'neduveryhodny_vek_jednatele'=>$neduveryhodny_vek_jednatele,
                'bydleni_mimo_eu'=>$bydleni_mimo_eu,
                'pocet_jinych_subjektu'=>$pocet_jinych_subjektu,
                'pocet_jinych_v_likvidaci'=>$pocet_jinych_v_likvidaci,
                'insolvence'=>$dluzniky,
                'oz'=>$oz,
                'status_domeny'=>$status_domeny,
                'domena_let_v_provozu'=>$domena_let_v_provozu,
                'pocet_provozoven'=>$pocet_provozoven,
                'jine_subjekty_na_sidle'=>$jine_subjekty_na_sidle,
                'aktualni_nabidky_prace'=>$volne_nabidky_prace,
                'test_zakladni'=>$test_zakldani,
                'test_jednatelu'=>$test_jednatelu,
                'test_samotneho_subjekta'=>$test_samotneho_subjekta,
                'test_domeny'=>$test_domeny,
                'test_bonusovy'=>$test_bonusovy,
                'vysledek'=>$vysledek
            ]);
        }

        public static function findAll()
        {
            $db=Db::get();
            $s=$db->prepare('SELECT ICO FROM firm');
            $s->execute();
            $array=$s->fetchAll(PDO::FETCH_ASSOC);
            $result=[];
            foreach ($array as$item)
            {
                array_push($result,$item['ico']);
            }
            return $result;

        }
        public static function findByIco($ico)
        {
            $db=Db::get();
            $s=$db->prepare('SELECT * FROM firm WHERE ico=:ico ');
            $s->execute([
                'ico' => $ico
            ]);
            return $s->fetch(PDO::FETCH_ASSOC);
        }
        public static function update_zakladni_test($score,$ico)
        {
            $db=Db::get();
            $s=$db->prepare('UPDATE firm set test_zakladni=:test_zakladni where ico=:ico');
            $s->execute([
                'ico'=>$ico,
                'test_zakladni'=>$score
            ]);
        }
         public static function update_test_jednatelu($score,$ico)
        {
        $db=Db::get();
        $s=$db->prepare('UPDATE firm set test_jednatelu=:test_jednatelu where ico=:ico');
        $s->execute([
            'ico'=>$ico,
            'test_jednatelu'=>$score
        ]);
        }

    public static function update_test_subjekta($score,$ico)
    {
        $db=Db::get();
        $s=$db->prepare('UPDATE firm set test_samotneho_subjekta=:test_samotneho_subjekta where ico=:ico');
        $s->execute([
            'ico'=>$ico,
            'test_samotneho_subjekta'=>$score
        ]);
    }

    public static function update_test_domeny($score,$ico)
    {
        $db=Db::get();
        $s=$db->prepare('UPDATE firm set test_domeny=:test_domeny where ico=:ico');
        $s->execute([
            'ico'=>$ico,
            'test_domeny'=>$score
        ]);
    }
    public static function update_test_bonusovy($score,$ico)
    {
        $db=Db::get();
        $s=$db->prepare('UPDATE firm set test_bonusovy=:test_bonusovy where ico=:ico');
        $s->execute([
            'ico'=>$ico,
            'test_bonusovy'=>$score
        ]);
    }
    public static function update_result($score,$ico)
    {
        $db=Db::get();
        $s=$db->prepare('UPDATE firm set vysledek=:vysledek where ico=:ico');
        $s->execute([
            'ico'=>$ico,
            'vysledek'=>$score
        ]);
    }
    public static  function update_bydleni($str,$ico)
    {
        $db=Db::get();
        $s=$db->prepare('UPDATE firm set bydleni_mimo_eu=:bydleni_mimo_eu where ico=:ico');
        $s->execute([
            'ico'=>$ico,
            'bydleni_mimo_eu'=>$str
        ]);
    }

}