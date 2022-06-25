<?php

namespace App\Service;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{

    private TileRepository $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $coordX, int $coordY): bool
    {


        $tile = $this->tileRepository->findOneBy(['coordX' => $coordX, 'coordY' => $coordY]);

        if ($tile != null) {

            return true;
        }
        return false;

        //return ($til!=null);

    }

    public function getRandomIsland():Tile{

        $Islandtiles = $this->tileRepository->findBy(['type'=>'island']);
        $IslandKey = array_rand($Islandtiles,1);
        return  $Islandtiles[$IslandKey] ;

    }

    public function checkTreasure(Boat $boat)
    {
        $RandomIsland=$this->tileRepository->findOneBy(['hasTreasure' => true]);

        if ($boat->getCoordX() == $RandomIsland->getCoordX() and $boat->getCoordY() == $RandomIsland->getCoordY() ){

            return true;
        }
    return false;
    }
}
