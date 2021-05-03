<?php


namespace App\Controller;

use App\Entity\Account;
use App\Entity\Firm;
use App\Form\AccountType;
use App\Form\SaveType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\AccessException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/account")
 * Class AccountController
 * @package App\Controller
 */
class AccountController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/create", name="account_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAccount(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account)
            ->add('submit', SubmitType::class, ['label' => 'Create'])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();
            $this->addFlash('success', 'Account was successfully created');
            return $this->redirectToRoute("home");
        }

        return $this->render("createAccount.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/history", name="history")
     * @param $id
     * @return Response
     */
    public function getHistory($id): Response
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->find(intval($id));
        if($account === null)
            return $this->render('404.html.twig',[]);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        try {
            $this->denyAccessUnlessGranted('edit', intval($id));
        } catch (\Exception $exception) {
           return  $this->render("403.html.twig",[]);
        }

        return $this->render("historyAccount.html.twig",['acc'=>$account]);
    }


    /**
     * @Route("/{id}/save/{idFirm}", methods={"GET","POST"}, name="save_result")
     * @param $idFirm
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function save_result($idFirm,$id,Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $account = $em->find(Account::class, intval($id));
        $firm = $em->find(Firm::class, intval($idFirm));
        if($firm === null || $account ===  null)
            return $this->render('404.html.twig',[]);

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        try {
            $this->denyAccessUnlessGranted('edit', intval($id));
        } catch (\Exception $exception) {
            return  $this->render("403.html.twig",[]);
        }

        $form = $this->createForm(SaveType::class)
            ->add('submit', SubmitType::class, ['label' => 'Uložit'])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $res = $form->getData();
            if($res['saveChoise'])
            {
                $firm->setAccount($account);
                $em->flush();
                $this->addFlash('success', 'Account was successfully updated');
            }
            return $this->redirectToRoute('result_page',[
                'id' => $firm->getId(),
            ]);
        }
        return $this->render("resultSafe.html.twig", ['account' => $account,
            'form' => $form->createView()]);
    }



}
