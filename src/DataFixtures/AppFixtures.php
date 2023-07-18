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

        // ! Creation Admin
        $admin = new User;
        $admin->setEmail('admin@mail.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'azerty');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);

        // ! Creation User
        $user = new User;
        $user->setEmail('user@mail.fr');
        $user->setRoles(['ROLE_USER']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'azerty');
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        
        // ? Creation Actor
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

        // ? Creation Writer
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

        // ? Creation Genre
        for($i =0; $i < 30; $i++){
            $genre = new Genre;
            $genre->setTitle("genre " . $i);

            $this->addReference('genre_' . $i, $genre);

            $manager->persist($genre);
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
            $movie->setPoster('default.jpg');
            $movie->setSlug($this->slugger->slug($movie->getTitle()));
            $numberActor = rand(3, 7);
            $numberWriter = rand(3, 7);
            $numberGenre = rand(3, 7);

            for($j = 0; $j < $numberActor; $j++){
                $actorReference = $this->getReference('actor_' . $i);
                $movie->addActor($actorReference);
            }

            for($j = 0; $j < $numberWriter; $j++){
                $writerReference = $this->getReference('writer_' . $i);
                $movie->addWriter($writerReference);
            }

            for($j = 0; $j < $numberGenre; $j++){
                $genreReference = $this->getReference('genre_' . $i);
                $movie->addGenre($genreReference);
            }


            $manager->persist($movie);
        }






        $manager->flush();
    }
}
