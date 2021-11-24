<?php

use App\Entity\Beer;

class Bartender
{

    /**
     * @input Array $packet : packet fetched from API
     * @return array beer list (name+description)
     */
    function filterPacket(): array
    {
        $beerListNameDescriptionDate = [];
        $packet = BeerConnectionManager::getPacket();
        //Extract needed params for each object
        for ($i = 0; $i < count($packet); $i++) {
            $temp = new Beer;
            $temp->setName($packet[$i]->name)
                ->setDescription($packet[$i]->description);
            //Push the beers into $temp
            array_push($beerListNameDescriptionDate, $temp);
        }
        return $beerListNameDescriptionDate;
    }


    /**
     * @return array : beer list (name+name)
     */
    public function filterBeerList(): array
    {
        $FilteredBeerNameList = $this->filterPacket();
        $beerListNameName = [];
        for ($i = 0; $i < count($FilteredBeerNameList); $i++) {
            array_push($beerListNameName, [$FilteredBeerNameList[$i]
                ->getName() => $FilteredBeerNameList[$i]->getName()]);
        }
        return $beerListNameName;
    }
}
