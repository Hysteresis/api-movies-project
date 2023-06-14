<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\User;
use App\Entity\Writer;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $admin = new User;
        $admin->setEmail('admin@mil.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        for($i =0; $i < 30; $i++){
            $actor = new Actor;
            $actor->setLastName("Doe" . $i);
            $actor->setFirstName("John");
            $day = rand(10, 30);
            $month = rand(1, 12);
            $year = rand(1950, 2000);
            $actor->setBirthDate($day . "/" . $month . "/" .$year);

            $this->addReference('actor_' .$i, $actor);

            $manager->persist($actor);
        }

        // for($i =0; $i < 30; $i++){
        //     $writer = new Writer;
        //     $writer->setLastName("Doe");
        //     $writer->setFirstName("John");
        //     $day = rand(10, 30);
        //     $month = rand(1, 12);
        //     $year = rand(1950, 2000);
        //     $writer->setBirthDate($day . "/" . $month . "/" .$year);
            

        //     $manager->persist($writer);
        // }

        for($i =0; $i < 30; $i++){
            $writer = new Writer;
            $writer->setLastName("Realistor" . $i);
            $writer->setFirstName("John");
            $day = rand(10, 30);
            $month = rand(1, 12);
            $year = rand(1950, 2000);
            $writer->setBirthDate($day . "/" . $month . "/" .$year);

            $this->addReference('writer_' .$i, $writer);

            $manager->persist($writer);
        }



        for($i =0; $i < 30; $i++){
            $movie = new Movie;
            $movie->setTitle('film' . $i);
            $rated = rand(3, 16);
            $movie->setRated($rated . '+');
            $movie->setReleased(new \DateTime('06/04/2020'));
            $minute = rand(10, 55);
            $movie->setRuntime("1h" . $minute);
            $movie->setPlot("Ceci est la description du film " . $i . "-> In non castra Paulus squalorem castra uncosque plures nullos enim Constantio plures multos uncosque proscripti actique nullos ad sunt multos movebantur alii.");
            $movie->setPoster('default.png');
            $movie->setSlug($this->slugger->slug($movie->getTitle()));
            $numberActor = rand(3, 7);
            $numberWriter = rand(3, 7);
            
            for($j = 0; $j < $numberActor; $j++){
                $actorReference = $this->getReference('actor_' . $i);
                $movie->addActor($actorReference);
            }

            for($j = 0; $j < $numberWriter; $j++){
                $writerReference = $this->getReference('writer_' . $i);
                $movie->addWriter($writerReference);
            }


            $manager->persist($movie);
        }




        for($i =0; $i < 10; $i++){
            $genre = new Genre;
            $genre->setTitle("genre " . $i);

            $manager->persist($genre);
        }

        $manager->flush();
    }
}
