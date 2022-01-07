<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 1; $i <= 10; $i++){
            $article = new Article();
            $article->setTitle("Titre de l'article $i")
                    ->setContent("<p>Contenu de l'article $i</p>")
                    ->setImage("placehold.it/350X200")
                    ->setCreateAt(new DateTime());

            // Demander au manager de persister
            $manager->persist($article);
        }

        $manager->flush();
    }
}
