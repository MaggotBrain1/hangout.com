<?php

namespace App\Controller;

use App\Entity\Hangout;
use App\Entity\User;
use App\Form\FilterType;
use App\Repository\HangoutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HangoutRepository $hangoutRepository, Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if($user == null)
        {
            $this->addFlash('fail','Vous devez être connecté pour voir les sorties!');
            return $this->redirectToRoute('app_login');
        }
        $hangout = new Hangout();
        $filterForm = $this->createForm(FilterType::class,$hangout);

        $filterForm->handleRequest($request);

        if($filterForm->isSubmitted() && $filterForm->isValid()){
            $campus = $filterForm["campusOrganizerSite"]->getData();
            $name = $filterForm["name"]->getData();
            $startDate = $filterForm["startDate"]->getData();
            $endDate= $filterForm["endDate"]->getData();
            $imOrginizer= $filterForm["isOrganizer"]->getData();
            $imIn= $filterForm["isSubscribe"]->getData();
            $imNotIn= $filterForm["isNotSuscribe"]->getData();
            $pastHangout= $filterForm["isHangoutPassed"]->getData();
            $hangouts = $hangoutRepository->findByFilter($campus,$name,$startDate,$endDate,$imOrginizer,$imIn,$imNotIn,$pastHangout,$user);
        }else{
            $hangouts = $hangoutRepository->findAll();

        }

        return $this->render('hangout/hangoutList.html.twig', [
            'hangouts' => $hangouts,
            'filterForm' => $filterForm->createView(),
            'subscribe'=>$hangoutRepository,
        ]);
    }
}
