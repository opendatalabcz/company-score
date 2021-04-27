<?php


namespace App\Controller;

use App\Entity\Account;
use App\Entity\Firm;
use App\Form\AccountType;
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
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if($account === null)
            return $this->render('404.html.twig',[]);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $firm = $this->getDoctrine()->getRepository(Firm::class)->findOneBy(['account'=>$id]);
        if($firm===null)
            $firm = new Firm();
        try {
            $this->denyAccessUnlessGranted('edit', intval($id));
        } catch (\Exception $exception) {
           return  $this->render("403.html.twig",[]);
        }

        return $this->render("historyAccount.html.twig",['acc'=>$account]);
    }

}
