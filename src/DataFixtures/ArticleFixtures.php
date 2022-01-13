<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        // for ($i = 1; $i <= 10; $i++){
        //     $article = new Article();
        //     $article->setTitle("Titre de l'article $i")
        //             ->setContent("<p>Contenu de l'article $i</p>")
        //             ->setImage("placehold.it/350X200")
        //             ->setCreateAt(new DateTime());

        //     // Demander au manager de persister
        //     $manager->persist($article);
        // }

        // $manager->flush();

        $faker = \Faker\Factory::create('fr_FR');
        for($i=0; $i<3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
            ->setDescription($faker->paragraph());

            $manager->persist($category);

            for($j=0; $j<mt_rand(4,6); $j++){
                $article = new Article();
                $content = '<p>';
                $content .= implode('</p><p>', $faker->paragraphs(5));
                $content .= '</p>';

                $article->setTitle($faker->sentence())
                ->setContent($content)
                ->setImage('http://picsum.photos/id/'.mt_rand(1,200).'/400/300')
                ->setCreateAt($faker->dateTimeBetween('-6 months', 'now'))
                ->setCategory($category);

                $manager->persist($article);

                for($k = 0; $k<mt_rand(4,10); $k++){
                    $comment = new Comment();
                    $content = '<p>';
                    $content .= implode('</p><p>', $faker->paragraphs(2));
                    $content .= '</p>';

                    $now = new DateTime();
                    $interval = $now->diff($article->getCreateAt());
                    $days = $interval->days;
                    $minimum = '-' . $days . ' days';

                    $comment->setAuthor($faker->name)
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween($minimum))
                    ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
