<?php


namespace App\Controller;


use App\Entity\BonusovyTest;
use App\Entity\Firm;
use App\Entity\TestDomeny;
use App\Entity\TestJednatelu;
use App\Entity\TestSubjektu;
use App\Form\TestType;
use App\Service\FirmService;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Whois;

class FormsController extends AbstractController
{

    private FirmService $firmService;

    /**
     * HomepageController constructor.
     * @param FirmService $firmService
     */
    public function __construct(FirmService $firmService)
    {
        $this->firmService = $firmService;
    }

    /**
     * @Route("/forms/{id}", methods={"GET","POST"}, name="forms_completing")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function forms_action($id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $firm = $this->getDoctrine()->getRepository(Firm::class)->findOneBy(['id' => $id]);
        if($firm === null)
            return $this->render('404.html.twig',[]);
        $ico = $firm->getIco();
        $form = $this->createForm(TestType::class)
            ->add('submit', SubmitType::class, ['label' => 'Otestovat!',
                'attr' => ['onclick' => 'MyClick()']])
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tests = $form->getData();
            //-------------------------------------------------------//
            $firm->setTestJednateluId(null);
            $test_jednatelu = $tests['test_jednatelu'];
            $test_jednatelu[] = 'jine_subjekty';
            $test_jednatelu[] = 'insolvence';
            $test_jednatelu[] = 'bydliste';
            try {
                $this->test_jednatelu($ico, $test_jednatelu, $firm->getId());
            } catch (\InvalidArgumentException  $e) {
                return $this->render("500.html.twig", array());
            }
            //-------------------------------------------------------//
            $firm->setTestSubjektuId(null);
            $test_subjektu = $tests['test_subjektu'];
            $test_subjektu[] = 'pocet_zamestnancu';
            $test_subjektu[] = 'dph';
            $test_subjektu[] = 'pocet_let_na_trhu';
            try {
                $this->test_subjektu($ico, $test_subjektu, $firm->getId());
            } catch (\InvalidArgumentException  $e) {
                return $this->render("500.html.twig", array());
            }
            //-------------------------------------------------------//
            $firm->setTestDomenyId(null);
            $test_domeny = $tests['test_domeny'];
            $test_domeny[] = 'existence';
            $test_domeny[] = 'pocet_let_v_provozu';
            try {
                $this->test_domeny($ico, $test_domeny, $firm->getId());
            } catch (\InvalidArgumentException  $e) {
                return $this->render("500.html.twig", array());
            }
            //-------------------------------------------------------//
            $firm->setBonusovyTestId(null);
            $test_bonusovy = $tests['test_bonusovy'];
            if (!empty($test_bonusovy)) {
                try {
                    $this->test_bonusovy($ico, $test_bonusovy, $firm->getId());
                } catch (\InvalidArgumentException  $e) {
                    return $this->render("500.html.twig", array());
                }
            }
            $em->flush();

            return $this->redirectToRoute('result_page', [
                'id' => $firm->getId()
            ]);
        }
        return $this->render('forms.html.twig', array(
            'form' => $form->createView(),
            'firm' => $firm,
        ));
    }


    public function test_jednatelu($ico, $array, $id): void
    {
        $this->firmService->pravni_forma($ico);
        $this->firmService->people_info($ico);
        $this->firmService->bydleni_jednatelu();
        $tj = new TestJednatelu();
        $pj = $this->firmService->getResultArray()['Pocet jednatelu'];
        $pj = intval($pj);
        $result = 0;
        foreach ($array as $test) {
            if ($test === 'pocet_jednatelu') {
                if (($pj > 5 && $this->firmService->getResultArray()['Pravni forma'] === 's.r.o.') || $pj === 0 || $pj === null)
                    $tj->setPocetJednatelu(0);
                else {
                    $tj->setPocetJednatelu(2);
                    $result += 2;
                }

            }
            if ($test === 'jine_subjekty') {
                if ($this->firmService->getResultArray()['Pocet jinych subjektu'] > ($pj * 6))
                    $tj->setPocetJinychSubjektu(0);
                else {
                    $result += 2;
                    $tj->setPocetJinychSubjektu(2);
                }

                if ($this->firmService->getResultArray()['Pocet jinych subjektu v likvidaci'] > ($pj * 2))
                    $tj->setPocetJinychSubjektuVLikvidaci(0);
                else {
                    $result += 2;
                    $tj->setPocetJinychSubjektuVLikvidaci(2);
                }
            }
            if ($test === 'insolvence') {
                if ($this->firmService->getResultArray()['Dluzniky'] === 'ANO' || $this->firmService->getResultArray()['Dluzniky'] === null)
                    $tj->setInsolvence(0);
                else {
                    $result += 4;
                    $tj->setInsolvence(4);
                }
            }
            if ($test === 'atypycky_vek') {
                if ($this->firmService->getResultArray()['Neduveryhodny vek jednatele'] === 'ANO' || $this->firmService->getResultArray()['Neduveryhodny vek jednatele'] === null)
                    $tj->setNetypyckyVekJednatele(0);
                else {
                    $result += 2;
                    $tj->setNetypyckyVekJednatele(2);
                }
            }
            if ($test === 'bydliste') {
                if ($this->firmService->getResultArray()['Bydleni mimo EU'] === 'NE') {
                    $result += 2;
                    $tj->setBydleniMimoEu(2);
                } else
                    $tj->setBydleniMimoEu(0);
            }
        }
        $tj->setResult($result);
        $em = $this->getDoctrine()->getManager();
        $em->persist($tj);

        $firm = $this->getDoctrine()->getRepository(Firm::class)->findOneBy(['id' => $id]);;
        $firm->setTestJednateluId($tj);

        $em->flush();
    }

    public function test_subjektu($ico, $array, $id)
    {
        $this->firmService->main_info($ico);
        $this->firmService->jine_firmy_na_sidle($ico);
        $this->firmService->pocet_zamestnancu($ico);
        $this->firmService->dph($ico);

        $ts = new TestSubjektu();
        $result = 0;

        foreach ($array as $test) {
            if ($test === 'pocet_zamestnancu') {
                $pz = $this->firmService->getResultArray()['Pocet zamestnancu'];
                $pz = intval($pz);
                switch ($pz) {
                    case ($pz > 0 && $pz <= 10):
                    {
                        $ts->setPocetZamestnancu(1);
                        $result += 1;
                        break;
                    }
                    case ($pz > 10 && $pz <= 50):
                    {
                        $ts->setPocetZamestnancu(2);
                        $result += 2;
                        break;
                    }
                    case ($pz > 50 && $pz <= 250):
                    {
                        $ts->setPocetZamestnancu(3);
                        $result += 3;
                        break;
                    }
                    case ($pz > 250 && $pz <= 1000):
                    {
                        $ts->setPocetZamestnancu(4);
                        $result += 4;
                        break;
                    }
                    case ($pz > 1000):
                    {
                        $ts->setPocetZamestnancu(5);
                        $result += 5;
                        break;
                    }
                    default:
                        $ts->setPocetZamestnancu(0);
                }
            }
            if ($test === 'pocet_let_na_trhu') {
                $pl = $this->firmService->getResultArray()['Pocet let na trhu'];
                $pl = intval($pl);
                switch ($pl) {
                    case ($pl > 0 && $pl <= 3):
                    {
                        $ts->setPocetLetNaTrhu(1);
                        $result += 1;
                        break;
                    }
                    case ($pl > 3 && $pl <= 10):
                    {
                        $ts->setPocetLetNaTrhu(2);
                        $result += 2;
                        break;
                    }
                    case ($pl > 10 && $pl <= 20):
                    {
                        $ts->setPocetLetNaTrhu(3);
                        $result += 3;
                        break;
                    }
                    case ($pl > 20):
                    {
                        $ts->setPocetLetNaTrhu(4);
                        $result += 4;
                        break;
                    }
                    default:
                        $ts->setPocetLetNaTrhu(0);
                        break;
                }
            }
            if ($test === 'dph') {
                if ($this->firmService->getResultArray()['Nespolehlivy platce'] === 'NE') {
                    $result += 4;
                    $ts->setNespolehlivyPlatce(4);
                } else
                    $ts->setNespolehlivyPlatce(0);
            }
            if ($test === 'jine_subjekty_na_sidle') {
                $jns = $this->firmService->getResultArray()['Jine subjekty na sidle'];
                $jns = intval($jns);
                switch ($jns) {
                    case ($jns >= 0 && $jns <= 15):
                    {
                        $ts->setJineSubjektyNaSidle(3);
                        $result += 3;
                        break;
                    }
                    case ($jns > 15 && $jns <= 30):
                    {
                        $ts->setJineSubjektyNaSidle(2);
                        $result += 2;
                        break;
                    }
                    case ($jns > 30 && $jns <= 80):
                    {
                        $ts->setJineSubjektyNaSidle(1);
                        $result += 1;
                        break;
                    }
                    default:
                    {
                        $ts->setJineSubjektyNaSidle(0);
                        break;
                    }
                }
            }
        }
        $ts->setResult($result);
        $em = $this->getDoctrine()->getManager();
        $em->persist($ts);

        $firm = $this->getDoctrine()->getRepository(Firm::class)->findOneBy(['id' => $id]);;
        $firm->setTestSubjektuId($ts);

        $em->flush();
    }

    public function test_domeny($ico, $array, $id)
    {
        $this->firmService->domena_info($ico);
        $className = 'Whois';
        $whois = new $className();
        $result = $whois->Lookup($this->firmService->getDm());
        $this->firmService->whois_parser($result);
        $this->firmService->whois_parser2($result);
        $td = new TestDomeny();
        $result = 0;
        foreach ($array as $test) {
            if ($test === 'existence') {
                if ($this->firmService->getResultArray()['Status domeny'] === 'ANO') {
                    $result += 4;
                    $td->setStatus(4);
                } else
                    $td->setStatus(0);
            }
            if ($test === 'pocet_let_v_provozu') {
                ;
                if (intval($this->firmService->getResultArray()['Domena(let v provozu)']) <= 2)
                    $td->setPocetLetVProvozu(0);
                else {
                    $result += 2;
                    $td->setPocetLetVProvozu(2);
                }
            }
            if ($test === 'posledni_modifikace') {
                if (intval($this->firmService->getResultArray()['Domena(posledni modifikace)']) <= 1) {
                    $td->setPosledniModifikace(2);
                    $result += 2;
                } else
                    $td->setPosledniModifikace(0);
            }
        }
        $td->setResult($result);
        $em = $this->getDoctrine()->getManager();
        $em->persist($td);

        $firm = $this->getDoctrine()->getRepository(Firm::class)->findOneBy(['id' => $id]);;
        $firm->setTestDomenyId($td);

        $em->flush();
    }

    public function test_bonusovy($ico, $array, $id)
    {
        $this->firmService->nabidky_prace($ico);
        $this->firmService->zakladni_kapital($ico);
        $this->firmService->pocet_provozoven($ico);
        $this->firmService->main_info($ico);
        $this->firmService->ochranne_znamky($this->firmService->getResultArray()['Nazev']);

        $tb = new BonusovyTest();
        $result = 0;
        foreach ($array as $test) {
            if ($test === 'aktualni_nabidky_prace') {
                if ($this->firmService->getResultArray()['Volne nabidky prace'] === 'ANO') {
                    $result += 1;
                    $tb->setAktualniNabidkyPrace(1);
                } else
                    $tb->setAktualniNabidkyPrace(0);
            }
            if ($test === 'pocet_provozoven') {
                $pp = $this->firmService->getResultArray()['Pocet provozoven'];
                $pp = intval($pp);
                switch ($pp) {
                    case ($pp > 0 && $pp <= 7):
                    {
                        $tb->setPocetProvozoven(1);
                        $result += 1;
                        break;
                    }
                    case ($pp > 7 && $pp <= 15):
                    {
                        $tb->setPocetProvozoven(2);
                        $result += 2;
                        break;
                    }
                    case ($pp > 15):
                    {
                        $tb->setPocetProvozoven(3);
                        $result += 3;
                        break;
                    }
                    default:
                        $tb->setPocetProvozoven(0);
                        break;
                }
            }
            if ($test === 'rust_zakladniho_kapitala') {
                if ($this->firmService->getResultArray()['Rust zakladniho kapitala'] === 'ANO') {
                    $result += 1;
                    $tb->setRustZakladnihoKapitala(1);
                } else
                    $tb->setRustZakladnihoKapitala(0);
            }
            if ($test === 'oz') {
                if ($this->firmService->getResultArray()['Oz'] === 'ANO') {
                    $tb->setOchranneZnamky(1);
                    $result += 1;
                } else
                    $tb->setOchranneZnamky(0);
            }

        }
        $tb->setResult($result);
        $em = $this->getDoctrine()->getManager();
        $em->persist($tb);

        $firm = $this->getDoctrine()->getRepository(Firm::class)->findOneBy(['id' => $id]);;
        $firm->setBonusovyTestId($tb);

        $em->flush();
    }
}