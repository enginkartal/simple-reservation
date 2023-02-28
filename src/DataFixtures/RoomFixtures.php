<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
class RoomFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 30; $i++) {
            $room = new Room();
            $room->setName("Room $i");
            $room->setCapacity($i);
            $room->setRate(5);
            $room->setLight(true);
            $room->setWifi(true);
            $room->setAvailable(true);
            $manager->persist($room);
            $manager->flush();
        }
    }
}