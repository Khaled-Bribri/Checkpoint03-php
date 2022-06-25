<?php

namespace App\Controller;
use App\Entity\Boat;
use App\Service\MapManager;
use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/boat')]
class BoatController extends AbstractController
{
    #[Route('/move/{x}/{y}', name: 'moveBoat', requirements: ['x' => '\d+', 'y' => '\d+'])]
    public function moveBoat(int $x, int $y, BoatRepository $boatRepository, EntityManagerInterface $em): Response
    {
        $boat = $boatRepository->findOneBy([]);
        $boat->setCoordX($x);
        $boat->setCoordY($y);
        $em->flush();
        return $this->redirectToRoute('map');
    }

    #[Route('/direction/{direction}', name: 'moveDirection', requirements: ['direction' => '[NSEW]'])]
    public function moveDirection($direction, BoatRepository $boatRepository, EntityManagerInterface $em, MapManager $mapManager): Response
    {
        $boat = $boatRepository->findOneBy([]);
        $x = $boat->getCoordX();
        $y = $boat->getCoordY();

        switch ($direction) {
            case 'N':
                $boat->setCoordY($y - 1);
                break;
            case 'S':
                $boat->setCoordY($y + 1);
                break;
            case 'E':
                $boat->setCoordX($x - 1);
                break;
            case 'W':
                $boat->setCoordX($x + 1);
                break;
        }
        if($mapManager->tileExists($boat->getCoordX(),$boat->getcoordY()))
        {
            $em->persist($boat);
            $em->flush();
            if($mapManager->checkTreasure($boat)){
                $this->addFlash('success','Youpi the treasure is here');
            }
        }
        else {
            $this->addFlash('danger','this til does not exit go back');
        }

        return $this->redirectToRoute('map');
    }
}
