<?php

namespace App;
use PDO;

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
    protected $rust_zakladniho_kapitala;
    protected $pocet_jednatelu;
    protected $pocet_jinych_subjektu;
    protected $pocet_jinych_v_likvidaci;
    protected $oz;
    protected $status_domeny;

    /**
     * Firm constructor.
     * @param $nazev
     * @param $ICO
     * @param $sidlo
     * @param $status
     * @param $pocet_let_na_trhu
     * @param $pocet_zamestnancu
     * @param $pravni_forma
     * @param $rust_zakladniho_kapitala
     * @param $pocet_jednatelu
     * @param $pocet_jinych_subjektu
     * @param $pocet_jinych_v_likvidaci
     * @param $oz
     * @param $status_domeny
     */
    public function __construct($nazev, $ICO, $sidlo, $status, $pocet_let_na_trhu, $pocet_zamestnancu, $pravni_forma, $rust_zakladniho_kapitala, $pocet_jednatelu, $pocet_jinych_subjektu, $pocet_jinych_v_likvidaci, $oz, $status_domeny)
    {
        $this->nazev = $nazev;
        $this->ICO = $ICO;
        $this->sidlo = $sidlo;
        $this->status = $status;
        $this->pocet_let_na_trhu = $pocet_let_na_trhu;
        $this->pocet_zamestnancu = $pocet_zamestnancu;
        $this->pravni_forma = $pravni_forma;
        $this->rust_zakladniho_kapitala = $rust_zakladniho_kapitala;
        $this->pocet_jednatelu = $pocet_jednatelu;
        $this->pocet_jinych_subjektu = $pocet_jinych_subjektu;
        $this->pocet_jinych_v_likvidaci = $pocet_jinych_v_likvidaci;
        $this->oz = $oz;
        $this->status_domeny = $status_domeny;
    }

    public static function createTable(): void
    {
        $db = Db::get();
        $db->query('CREATE TABLE IF NOT EXISTS `firm` (
            firm_id INTEGER PRIMARY KEY,
            nazev INTEGER,
            ico TEXT,
            sidlo TEXT,
            status TEXT,
            pocet_let_na_trhu INTEGER,
            pocet_zamestnancu INTEGER,
            pravni_forma TEXT,
            rust_zakladniho_kapitala TEXT,
            pocet_jednatelu INTEGER,
            pocet_jinych_subjektu INTEGER,
            pocet_jinych_v_likvidaci INTEGER,
            oz TEXT,
            status_domeny TEXT,
        )');
    }


}