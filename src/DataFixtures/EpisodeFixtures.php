<?php

namespace App\DataFixtures;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
         
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $episode = new episode(); 
            $episode->setTitle($faker->realText($maxNbChars = 50));;
            $episode->setNumber($faker->randomDigitNotNull);  
            $episode->setSynopsis($faker->realText($maxNbChars = 255));
            $episode->setSeason($this->getReference('season_' . $faker->numberBetween(0,49)));

            $slug = $this->slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()  
    {
        return [SeasonFixtures::class];  
    }
}