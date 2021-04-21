<?php

namespace App;





class Score
{

    public function Zakladni_test_count($ico)
    {
        $score=0;
        $firm=Firm::findByIco($ico);
        if($firm['status']==='NE')
            $score+=2;

        Firm::update_zakladni_test($score,$ico);
    }
    public  function Jednatelu_test_count($ico)
    {
        $score=11;
        $firm=Firm::findByIco($ico);
        if(intval($firm['test_zakladni'])!==0)
        {
            $pj=$firm['pocet_jednatelu'];
            $pj=intval($pj);
            if(($pj>5 && $firm['pravni_forma']==='s.r.o.') || $pj===0)
                $score-=2;
            if($firm['pocet_jinych_subjektu']>($pj*6))
                $score-=2;
            if($firm['neduveryhodny_vek_jednatele']==='ANO' || $firm['neduveryhodny_vek_jednatele']===null)
                $score-=2;
            if($firm['pocet_jinych_v_likvidaci']>($pj*2))
                $score-=2;
            if($firm['insolvence']==='ANO' ||$firm['insolvence']===null )
                $score-=3;
            if($firm['bydleni_mimo_eu']=='NE')
                $score+=2;

            Firm::update_test_jednatelu($score,$ico);
        }
        else
        {
            Firm::update_test_jednatelu(0,$ico);
        }

    }
    public  function Subjekt_test_count($ico)
    {
        $score=0;
        $firm=Firm::findByIco($ico);

        if(intval($firm['test_zakladni'])!==0)
        {
            $pz=$firm['pocet_zamestnancu'];
            $pz=intval($pz);
            switch ($pz) {
                case ($pz>0 && $pz<=10):
                    $score+=1;
                    break;
                case ($pz>10 && $pz<=50):
                    $score+=2;
                    break;
                case ($pz>50 && $pz<=250):
                    $score+=3;
                    break;
                case ($pz>250 && $pz<=1000):
                    $score+=4;
                    break;
                case ($pz>1000):
                    $score+=5;
                    break;
            }

            $pl=$firm['pocet_let_na_trhu'];
            $pl=intval($pl);
            switch ($pl) {
                case ($pl>0 && $pl<=3):
                    $score+=1;
                    break;
                case ($pl>3 && $pl<=10):
                    $score+=2;
                    break;
                case ($pl>10 && $pl<=20):
                    $score+=3;
                    break;
                case ($pl>20):
                    $score+=4;
                    break;
            }

            if($firm['nespolehlivy_platce']==='NE')
                $score+=3;

            $jns=$firm['jine_subjekty_na_sidle'];
            $jns=intval($jns);
            switch ($jns) {
                case ($jns>0 && $jns<=15):
                    $score+=3;
                    break;
                case ($jns>15 && $jns<=30):
                    $score+=2;
                    break;
                case ($jns>30 && $jns<=80):
                    $score+=1;
                    break;
            }

            Firm::update_test_subjekta($score,$ico);
        }
        else
        {
            Firm::update_test_subjekta(0,$ico);
        }


    }
    public  function Domena_test_count($ico)
    {
        $score=2;
        $firm=Firm::findByIco($ico);

        if($firm['status_domeny']==='ANO')
            $score+=5;

        if($firm['domena_let_v_provozu'] < 2)
            $score-=2;

        Firm::update_test_domeny($score,$ico);
    }
    public  function Bonusovy_test_count($ico)
    {
        $score=0;
        $firm=Firm::findByIco($ico);

        if(intval($firm['test_zakladni']))
        {
            if($firm['aktualni_nabidky_prace']==='ANO')
                $score+=1;

            if($firm['rust_zakladniho_kapitala']==='ANO')
                $score+=1;

            if($firm['oz']==='ANO')
                $score +=1;

            $pp=$firm['pocet_provozoven'];
            $pp=intval($pp);
            switch ($pp) {
                case ($pp>0 && $pp<=7):
                    $score+=1;
                    break;
                case ($pp>7 && $pp<=15):
                    $score+=2;
                    break;
                case ($pp>15):
                    $score+=3;
                    break;
            }

            Firm::update_test_bonusovy($score,$ico);
        }
        else
        {
            Firm::update_test_bonusovy(0,$ico);
        }

    }

    public  function Vysledek($ico)
    {
        $result=0;
        $firm=Firm::findByIco($ico);
        $array=["test_zakladni","test_bonusovy","test_jednatelu","test_samotneho_subjekta","test_domeny"];
        foreach ($array as  $item)
        {
            $result+=intval($firm[$item]);
        }

        Firm::update_result($result,$ico);
    }
}