<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;
class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 30; $i++) {
            $date = new DateTime();
            $customer = new Customer();
            $customer->setFirstName('name ' . $i);
            $customer->setLastName('lastname ' . $i);
            $customer->setEmail('mail_' . $i . '@test.com');
            $customer->setPhoneNumber('1234567890' . $i);
            $customer->setCreatedAt($date);
            $manager->persist($customer);
            $manager->flush();
        }
    }
}