<?php


namespace App\Controller\Rest;


use App\Entity\Account;
use App\Entity\Firm;
use App\Form\AccountType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ArticleController
 * @package App\Controller\Rest
 * @Rest\View(serializerEnableMaxDepthChecks=true)
 */
class AccountRestController extends AbstractFOSRestController
{
    /**
     * @Rest\Put("/account/{id}")
     * @param $id
     * @param Request $fetcher
     * @return Response
     *
     */
    public function uodateAccount ( $id, Request $fetcher )
    {
        $fetcher=json_decode($fetcher->getContent(),true);

        $em = $this->getDoctrine()->getManager();
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);
        $firm = $this->getDoctrine()->getRepository(Firm::class)->find($fetcher);
        $firm->setAccount($account);
        $em->flush();
        $view= $this->view($account,200);
        return $this->handleView($view);

    }

    /**
     * @Rest\Get("/account/{id}", name="api_get_account")
     * @param $id
     * @return Response
     */
    public function getAccount($id): Response
    {
        $employee = $this->getDoctrine()->getRepository(Account::class)->find($id);
        if (!$employee) {
            throw new NotFoundHttpException();
        }
        $view=$this->view($employee,200);
        return $this->handleView($view);
    }
}
