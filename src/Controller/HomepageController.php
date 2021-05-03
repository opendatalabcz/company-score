<?php


namespace App\Controller;


use App\Entity\Firm;
use App\Entity\ZakladniTest;
use App\Form\FirmType;
use App\Service\FirmService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
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
     * @Route("/", methods={"GET","POST"}, name="home")
     * @param Request $request
     * @return Response
     */
    public function homepage_actions(Request $request): Response
    {
        $firm = new Firm();
        $form = $this->createForm(FirmType::class, $firm)
            ->add('submit', SubmitType::class, ['label' => 'Hledat'])
            ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $firm = $form->getData();
            $ico = $firm->getIco();
            try{
            $firm = $this->firmService->createFirm($ico);
            } catch (\InvalidArgumentException  $e) {
                return $this->render("500.html.twig", array());
            }
            if ($firm !== null) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($firm);
                $entityManager->flush();
                $firm = $this->zakladni_test($firm->getId());
                if ($firm->getZakladniTestId()->getResult() === 0)
                    return $this->redirectToRoute('result_page', [
                        'id' => $firm->getId()
                    ]);
                return $this->redirectToRoute('forms_completing', [
                    'id' => $firm->getId(),
                ]);

            } else {
                $err = true;
                return $this->render('homepage.html.twig', array(
                    'err' => $err,
                    'form' => $form->createView()
                ));
            }
        }
        $err = false;
        return $this->render('homepage.html.twig', array(
            'err' => $err,
            'form' => $form->createView()
        ));
    }

    public function zakladni_test($id)
    {
        $zt = new ZakladniTest();
        if ($this->firmService->getResultArray()['Status(v likvidaci)'] === 'NE') {
            $zt->setStatus(2);
        } else
            $zt->setStatus(0);
        $zt->setResult($zt->getStatus());

        $em = $this->getDoctrine()->getManager();
        $em->persist($zt);


        $firm = $this->getDoctrine()->getRepository(Firm::class)->findOneBy(['id' => $id]);;
        $firm->setZakladniTestId($zt);

        $em->flush();

        return $firm;
    }

}