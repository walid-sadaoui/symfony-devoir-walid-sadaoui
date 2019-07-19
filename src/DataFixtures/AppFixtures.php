<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");

        for ($i = 1; $i <= 30; $i++){
            $article = new Article();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl();
            $excerpt = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>',
            $faker->paragraphs(5)) . '</p>';

            $article->setTitle($title)
                ->setCoverImage($coverImage)
                ->setContent($content)
                ->setExcerpt($excerpt)
                ->initializeSlug();

                for( $j = 0; $j <= mt_rand(2, 5); $j++){
                    $image = new Image();

                    $image->setUrl($faker->imageUrl())
                            ->setCaption($faker->sentence())
                            ->setArticle($article);
                    $manager->persist($image);
                }

            $manager->persist($article);
        }
        $manager->flush();
    }
}
