<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Génére 10 événements avec entre 5 et 15 participants
        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event->setName($faker->words(2, true));
            $event->setDate(
                \DateTimeImmutable::createFromMutable(
                    $faker->dateTimeBetween('now', '+1 year')
                )
            );
            $event->setLocationLongitude($faker->longitude);
            $event->setLocationLatitude($faker->latitude);

            for ($j = 0; $j < rand(5, 15); $j++) {
                $participant = new Participant();
                $participant->setName($faker->name);
                $participant->setEmail($faker->email);
                $participant->setLocationLatitude($faker->latitude);
                $participant->setLocationLongitude($faker->longitude);
                $participant->setEvent($event);

                $manager->persist($participant);
            }

            $manager->persist($event);
        }

        $manager->flush();
    }
}
