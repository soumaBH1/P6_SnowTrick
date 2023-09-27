<?php

namespace App\DataFixtures;


use Datetime;
use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        //créer 3 categories fakées
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                ->setDescription($faker->paragraph());
            $manager->persist($category);


            //créer entre et 6 articles:
            for ($j = 1; $j <= mt_rand(4, 6); $j++) {
                $article = new Article();
                $paragraphs = $faker->paragraphs(5); // Génère 5 paragraphes sous forme de tableau
                $content ="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."; //join('<p></p>',$faker->paragraph(5));
                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 month'))
                    ->setCategory($category);
                $manager->persist($article);

                //on cree des commentaire de l'article
                for ($k = 1; $k <= mt_rand(4, 10); $k++) {

                    $comment = new Comment();
                    $paragraphs = $faker->paragraphs(1); // Génère 5 paragraphes sous forme de tableau
                    $commentContent ="exemple de commentaire. "; //join('<p></p>',$faker->paragraph(1));
                    $now = new Datetime();
                    $interval = $now->diff($article->getCreatedAt()); //intervalle de  jours depuis la date de création de l'article
                    $days = $interval->days; //nombre de jours depuis la date de création de l'article
                    $minimum = '_' . $days . 'days';

                    $comment->setAuthor($faker->name)
                        ->setContent($commentContent)
                        ->setCreatedAt($faker->dateTimeBetween($minimum))
                        ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }


        $manager->flush();
    }
}
