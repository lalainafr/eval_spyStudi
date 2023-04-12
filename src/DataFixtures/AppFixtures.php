<?php

namespace App\DataFixtures;

use App\Entity\Agent;
use App\Entity\Speciality;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    // Implementer FAKER
    /**
     * @var Generator
     */
    private Generator $faker;


    private $reservations;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    // Mise en place des FIXTURES
    public function load(ObjectManager $manager)
    {

        //SPECIALITE
        $specialities = [];
        for ($i=0; $i < 5; $i++) { 
            $speciality = new Speciality();
            $speciality->setName('SPECIALITE - '. $this->faker->text(8));
            $manager->persist($speciality);
            $specialities[] =  $speciality;
        }


        //AGENT
        $agents = [];
        for ($i=0; $i < 5; $i++) { 
            $agent= new Agent();
            $agent->setfirstName($this->faker->firstName());
            $agent->setlastName($this->faker->lastName());
            $agent->setBirthDate($this->faker->dateTimeBetween('- 30 year', '- 20 year'));
            $agent->setIdCode($this->faker->text(8));
            for ($j=0; $j < mt_rand(1,3); $j++) { 
                $agent->addSpeciality($specialities[mt_rand(1, count($specialities) - 1)]);
            }
            $manager->persist($agent);
            $agents[] = $agent;
        }
        $manager->flush();
    }
}