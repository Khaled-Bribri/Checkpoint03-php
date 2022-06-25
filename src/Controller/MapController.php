<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;

class MapController extends AbstractController
{
    #[Route('/map', name: 'map')]
    public function displayMap(BoatRepository $boatRepository, TileRepository $tileRepository): Response
    {
        $tiles = $tileRepository->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
        ]);
    }

    #[Route('/start', name: 'start')]
    public function start(BoatRepository $boatRepository, EntityManagerInterface $em,TileRepository $tileRepository, MapManager $mapManager){

        $boat = $boatRepository->findOneBy([]);

        $boat->setCoordX(0);
        $boat->setCoordY(0);
        $em->persist($boat);
        $em->flush();

        $tiles = $tileRepository->findAll();

        foreach($tiles as $tile)
        {
            $tile->setHasTreasure(false);
            $em->persist($tile);
        }
        $em->flush();

        $RandomIsland = $mapManager->getRandomIsland();
        $RandomIsland->setHasTreasure(true);
        $em->persist($RandomIsland);
        $em->flush();



        return $this->redirectToRoute('map');
    }
}
