<?php

namespace App\DataFixtures;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $season = new Season();  
            $season->setNumber($faker->randomDigitNotNull);  
            $season->setYear($faker->year($min = '1930', $max = 'now'));
            $season->setDescription($faker->realText($maxNbChars = 255));
            $season->setProgram($this->getReference('program_' . $faker->numberBetween(0,5)));
            $this->addReference('season_' . $i, $season);
            $manager->persist($season);
        }
        $manager->flush();
    }

    public function getDependencies()  
    {
        return [ProgramFixtures::class];  
    }
}