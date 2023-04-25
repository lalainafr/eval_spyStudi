<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Type;
use Faker\Generator;
use App\Entity\Agent;
use App\Entity\Contact;
use App\Entity\Status;
use App\Entity\Country;
use App\Entity\Hideout;
use App\Entity\Mission;
use App\Entity\Speciality;
use App\Entity\Target;
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


        //COUNTRY
        for ($i = 0; $i < 4; $i++) {
            $country = new Country();
            $country->setName($this->faker->country());
            $manager->persist($country);
            $countries[] = $country;
        }

        //SPECIALITE
        $specialities = [];
        for ($i = 0; $i < 5; $i++) {
            $speciality = new Speciality();
            $speciality->setName('SPE - ' . $this->faker->word(8));
            $manager->persist($speciality);
            $specialities[] =  $speciality;
        }

        //AGENT
        $agents = [];
        for ($i = 0; $i < 5; $i++) {
            $agent = new Agent();
            $agent->setfirstName($this->faker->firstName());
            $agent->setlastName($this->faker->lastName());
            $agent->setBirthDate($this->faker->dateTimeBetween('- 30 year', '- 20 year'));
            $agent->setIdCode($this->faker->text(8));
            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                $agent->addSpeciality($specialities[mt_rand(0, count($specialities) - 1)]);
            }
            $agent->setNationality($countries[mt_rand(0, count($countries) - 1)]);
            $manager->persist($agent);
            $agents[] = $agent;
        }

        //TYPE
        $types =  ['Infiltration', 'Surveillance', 'Assassinat'];
        for ($i = 0; $i < 3; $i++) {
            $type = new Type();
            $type->setName($types[$i]);
            $manager->persist($type);
            $typesArray[] = $type;
        }

        //STATUT
        $statuses =  ['En preparation', 'En cours', 'Terminé', 'Echec'];
        for ($i = 0; $i < 4; $i++) {
            $status = new Status();
            $status->setName($statuses[$i]);
            $manager->persist($status);
            $statusArray[] = $status;
        }


        //PLANQUE
        for ($i = 0; $i < 4; $i++) {
            $hideout = new Hideout();
            $hideout->setCode('PLQ - ' . $this->faker->text(8));
            $hideout->setAddress($this->faker->address());
            $hideout->setType('PLTYPE - ' . $this->faker->text(8));
            $hideout->setCountry($countries[mt_rand(0, count($countries) - 1)]);
            $manager->persist($hideout);
            $hideouts[] = $hideout;
        }

        //CIBLE
        for ($i = 0; $i < 4; $i++) {
            $target = new Target();
            $target->setFirstName($this->faker->firstName());
            $target->setLastName($this->faker->lastName());
            $target->setBirthDate($this->faker->dateTimeBetween('-40 years', '-20years'));
            $target->setCodeName('CIB - ' . $this->faker->text(8));
            $target->setNationality($countries[mt_rand(0, count($countries) - 1)]);
            $manager->persist($target);
            $targets[] = $target;
        }

        //CONTACT
        for ($i = 0; $i < 4; $i++) {
            $contact = new Contact();
            $contact->setFirstName($this->faker->firstName());
            $contact->setLastName($this->faker->lastName());
            $contact->setBirthDate($this->faker->dateTimeBetween('-40 years', '-20years'));
            $contact->setCodeName('CTC - ' . $this->faker->text(8));
            $contact->setNationality($countries[mt_rand(0, count($countries) - 1)]);
            $manager->persist($contact);
            $contacts[] = $contact;
        }

        //MISSION
        $missions = [];
        for ($i = 0; $i < 5; $i++) {
            $mission = new Mission();
            $mission->setTitle('MIS - ' . $this->faker->word(10));
            $mission->setDescription($this->faker->text(100));
            $mission->setCodeName('COD - ' . $this->faker->word(8));
            $mission->setStartDate($this->faker->dateTimeBetween('+ 1 day', '+ 10 days'));
            $mission->setEndDate($this->faker->dateTimeBetween('+ 20 day', '+ 30 days'));
            $mission->addAgent($agents[mt_rand(0, count($agents) - 1)]);
            $mission->setType($typesArray[mt_rand(0, count($typesArray) - 1)]);
            $mission->setStatus($statusArray[mt_rand(0, count($statusArray) - 1)]);
            $mission->setCountry($countries[mt_rand(0, count($countries) - 1)]);
            $mission->setSpeciality($specialities[mt_rand(0, count($specialities) - 1)]);
            $mission->addHideout($hideouts[mt_rand(0, count($hideouts) - 1)]);
            $mission->addTarget($targets[mt_rand(0, count($targets) - 1)]);
            $mission->addContact($contacts[mt_rand(0, count($contacts) - 1)]);

            $manager->persist($mission);
            $missions[] = $mission;
        }

        $manager->flush();
    }
}
