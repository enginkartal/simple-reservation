<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class ReservationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 20; $i++) {
            $date = new DateTime();
            $checkIn = new DateTime();
            $checkOut = new DateTime();
            $checkIn = $checkIn->modify("+{$i} days");
            $checkOut = $checkOut->modify("+{$i} days")->modify("+{$i} days");

            $reservation = new Reservation();
            $reservation->setRef('ref' . $i);
            $reservation->setRoomId($i);
            $reservation->setCheckIn($checkIn);
            $reservation->setCheckOut($checkOut);
            $reservation->setAmount(145 * $i);
            $reservation->setCustomerId($i);
            $reservation->setCreatedAt($date);
            $manager->persist($reservation);
            $manager->flush();
        }
    }
}