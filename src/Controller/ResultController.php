<?php


namespace App\Controller;

use App\Entity\Account;
use App\Entity\BonusovyTest;
use App\Entity\Firm;
use App\Entity\TestDomeny;
use App\Entity\TestSubjektu;
use App\Entity\ZakladniTest;
use App\Entity\TestJednatelu;
use App\Form\SaveType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ResultController extends AbstractController
{


    /**
     * @Route("/result/{id}", methods={"GET"}, name="result_page")
     * @param $id
     * @return Response
     */
    public function result_action($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $firm = $this->getDoctrine()->getRepository(Firm::class)->find($id);
        if($firm === null)
            return $this->render('404.html.twig',[]);
        $tables = [];
        array_push($tables, $this->get_table_zakldani($firm->getZakladniTestId()));
        if ($firm->getTestJednateluId() !== null)
            array_push($tables, $this->get_table_jednatelu($firm->getTestJednateluId()));
        if ($firm->getTestSubjektuId() !== null)
            array_push($tables, $this->get_table_subjekt($firm->getTestSubjektuId()));
        if ($firm->getTestDomenyId() !== null)
            array_push($tables, $this->get_table_domena($firm->getTestDomenyId()));
        if ($firm->getBonusovyTestId() !== null)
            array_push($tables, $this->get_table_bonus($firm->getBonusovyTestId()));
        $body = 0;
        $max = 0;
        foreach ($tables as $table) {
            $body += intval($table['result']);
            foreach ($table['tests'] as $test) {
                $max = $max + intval($test['max']);
            }
        }
        $procento = round(($body * 100) / $max);
        $firm->setResult($procento);
        $zn=$this->get_right_znamka($procento);
        $em->flush();
        $link_on="https://or.justice.cz/ias/ui/rejstrik-%24firma?p%3A%3Asubmit=x&.%2Frejstrik-%24firma=&nazev=&ico=" . trim($firm->getIco()) . "&obec=&ulice=&forma=&oddil=&vlozka=&soud=&polozek=50&typHledani=STARTS_WITH&jenPlatne=VSECHNY";
        return $this->render('result.html.twig', [
            'firm' => $firm,
            'procento' => $procento,
            'tables' => $tables,
            'zn'=>$zn,
            'popis' =>$this->get_popis($zn),
            'link' =>$link_on
        ]);
    }

    public function get_popis($zn)
    {
        $popis='';
        if($zn === 'F')
            $popis="Toto hodnocení znamená, že společnost s tímto IČO je v likvidaci nebo byla vymazána z rejstříku.";
        if($zn === 'E')
            $popis ='Toto hodnocení znamená, že tato firma spada do skupiny, ve které existují problémy ve všech oblastech testování. Firmy této skupiny mají tendenci mít velmi malý počet zaměstnanců a také pracují méně než 5–10 let, navíc mají tyto organizace problémy s řídícími pracovníky (registr dlužníků, místo bydliště v podezřelých zemích nebo problémy většina z těchto organizací je rovněž na seznamu nedůvěryhodných plátců DPH. Kromě výše uvedeného neexistuje ani žádná doména.';
        if($zn === 'D')
            $popis ='Toto hodnocení znamená, že testovaná organizace spadala do skupiny firem, které se vyznačují malým počtem zaměstnanců, absencí domén. Kromě toho mají společnosti této skupiny nejčastěji problémy s placením DPH nebo majitelé této organizace jsou v registru dlužníků. Obecně lze tyto firmy charakterizovat jako malé organizace s problémy v některých oblastech. Tato skupina je poměrně početná.';
        if($zn === 'C')
            $popis ='Toto hodnocení znamená, že testovaná firma spadala do skupiny, která se vyznačuje poměrně dlouhou dobou práce, ale zároveň malým počtem zaměstnanců. Nejběžnějším problémem je nedostatek registrovaných domén. Ve vzácných případech existují problémy ve finančním sektoru (nespolehlivý plátce DPH). Obecně tato skupina, s výjimkou výše popsaných bodů, vzbuzuje důvěru a dlouhá doba práce to jen potvrzuje, nicméně je třeba věnovat pozornost popsaným problémům.';
        if($zn === 'B')
            $popis ='Tato organizace spadala do skupiny společností, které se vyznačují dlouhou dobou práce a vysokým počtem zaměstnanců. Mezi plusy patří také přítomnost domén a její dlouhá existence. Firmy patřící do této skupiny jsou velmi důvěryhodné, protože během testování nebyly zjištěny žádné problémy týkající se vlastníků nebo samotného subjektu.';
        if($zn === 'A')
            $popis ='Toto hodnocení znamená, že se firma dostala do skupiny zahrnující společnosti světové úrovně, což mluví samo za sebe. Během testování tyto organizace neodhalily žádné vážné problémy. Dlouhá doba práce (nejčastěji více než 20 let), stejně jako absence zjevných problémů, způsobují nejvyšší úroveň důvěry spojené s vlastníky nebo samotným subjektem, jakož i přítomnost domény a její dlouhodobé termín použití.';

        return $popis;
    }
    public function get_right_znamka($procento)
    {
        switch ($procento){
            case (0):
            {
                $zn='F';
                break;
            }
            case ($procento >0 && $procento <=35):
            {
                $zn='E';
                break;
            }
            case($procento>35 && $procento <=55):
            {
                $zn='D';
                break;
            }
            case ($procento>55 && $procento<=75):
            {
                $zn='C';
                break;
            }
            case ($procento>75 && $procento<=92):
            {
                $zn='B';
                break;
            }
            case ($procento>93):
            {
                $zn='A';
                break;
            }
            default:
            {
                $zn='Undefined';
                break;
            }
        }
        return $zn;
    }

    public function get_table_zakldani(ZakladniTest $zakladniTest)
    {
        return [
            'nazev' => 'Základní test',
            'tests' => [
                [
                    'name' => 'Status',
                    'real' => $zakladniTest->getStatus(),
                    'max' => 2
                ],
            ],
            'result' => $zakladniTest->getResult()
        ];
    }

    public function get_table_bonus(BonusovyTest $bonusovyTest)
    {
        $res = array(
            'nazev' => 'Bonusový test',
            'result' => $bonusovyTest->getResult(),
            'tests' => []
        );
        if ($bonusovyTest->getPocetProvozoven() !== null)
            $res['tests'][] = [
                'name' => 'Provozovny',
                'real' => $bonusovyTest->getPocetProvozoven(),
                'max' => 3
            ];
        if ($bonusovyTest->getOchranneZnamky() !== null)
            $res['tests'][] = [
                'name' => 'Ochranné známky',
                'real' => $bonusovyTest->getOchranneZnamky(),
                'max' => 1
            ];
        if ($bonusovyTest->getRustZakladnihoKapitala() !== null)
            $res['tests'][] = [
                'name' => 'Růst základního kapitála',
                'real' => $bonusovyTest->getRustZakladnihoKapitala(),
                'max' => 1
            ];
        if ($bonusovyTest->getAktualniNabidkyPrace() !== null)
            $res['tests'][] = [
                'name' => 'Aktuální nabídky práce',
                'real' => $bonusovyTest->getAktualniNabidkyPrace(),
                'max' => 1
            ];
        return $res;
    }

    public function get_table_domena(TestDomeny $testDomeny)
    {
        $res = array(
            'nazev' => 'Test domény',
            'result' => $testDomeny->getResult(),
            'tests' => []
        );
        if ($testDomeny->getStatus() !== null)
            $res['tests'][] = [
                'name' => 'Existence',
                'real' => $testDomeny->getStatus(),
                'max' => 4
            ];
        if ($testDomeny->getPocetLetVProvozu() !== null)
            $res['tests'][] = [
                'name' => 'Počet let v provozu',
                'real' => $testDomeny->getPocetLetVProvozu(),
                'max' => 2
            ];
        if ($testDomeny->getPosledniModifikace() !== null)
            $res['tests'][] = [
                'name' => 'Update domény',
                'real' => $testDomeny->getPosledniModifikace(),
                'max' => 2
            ];
        return $res;
    }

    public function get_table_subjekt(TestSubjektu $testSubjektu)
    {
        $res = array(
            'nazev' => 'Test subjektu',
            'result' => $testSubjektu->getResult(),
            'tests' => []
        );
        if ($testSubjektu->getJineSubjektyNaSidle() !== null)
            $res['tests'][] = [
                'name' => 'Sídlo',
                'real' => $testSubjektu->getJineSubjektyNaSidle(),
                'max' => 3
            ];
        if ($testSubjektu->getNespolehlivyPlatce() !== null)
            $res['tests'][] = [
                'name' => 'DPH',
                'real' => $testSubjektu->getNespolehlivyPlatce(),
                'max' => 4
            ];
        if ($testSubjektu->getPocetLetNaTrhu() !== null)
            $res['tests'][] = [
                'name' => 'Počet let na trhu',
                'real' => $testSubjektu->getPocetLetNaTrhu(),
                'max' => 4
            ];
        if ($testSubjektu->getPocetZamestnancu() !== null)
            $res['tests'][] = [
                'name' => 'Počet zaměstnanců',
                'real' => $testSubjektu->getPocetZamestnancu(),
                'max' => 5
            ];
        return $res;
    }

    public function get_table_jednatelu(TestJednatelu $testJednatelu)
    {
        $res = array(
            'nazev' => 'Test jednatelů',
            'result' => $testJednatelu->getResult(),
            'tests' => []
        );
        if ($testJednatelu->getPocetJednatelu() !== null)
            $res['tests'][] = [
                'name' => 'Počet jednatelů',
                'real' => $testJednatelu->getPocetJednatelu(),
                'max' => 2
            ];
        if ($testJednatelu->getNetypyckyVekJednatele() !== null)
            $res['tests'][] = [
                'name' => 'Netypický věk',
                'real' => $testJednatelu->getNetypyckyVekJednatele(),
                'max' => 2
            ];
        if ($testJednatelu->getBydleniMimoEu() !== null)
            $res['tests'][] = [
                'name' => 'Bydlení ve specifických zemích.',
                'real' => $testJednatelu->getBydleniMimoEu(),
                'max' => 2
            ];
        if ($testJednatelu->getInsolvence() !== null)
            $res['tests'][] = [
                'name' => 'Insolvence',
                'real' => $testJednatelu->getInsolvence(),
                'max' => 4
            ];
        if ($testJednatelu->getPocetJinychSubjektu() !== null)
            $res['tests'][] = [
                'name' => 'Jiné souvislé organizace',
                'real' => intval($testJednatelu->getPocetJinychSubjektu()) + intval($testJednatelu->getPocetJinychSubjektuVLikvidaci()),
                'max' => 4
            ];
        return $res;
    }
}