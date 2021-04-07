<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setUsername("caca");
        $user->setPassword("caca");
        $cat = new Category();
        $cat->setName("pipi");
        $manager->persist($user);
        $manager->persist($cat);
        for ($i = 0; $i < 4; ++$i) {
            $blog = new Blog();
            $blog->setTitle('Lorem ipsum');
            $blog->setBody('Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
              Proin sodales, arcu non commodo vulputate, neque lectus luctus metus, 
              ac hendrerit mi erat eu ante. Nullam blandit arcu erat,
              vitae pretium neque suscipit vitae. 
              Pellentesque sit amet lacus in metus placerat posuere. Aliquam hendrerit risus elit, non commodo nulla cursus id. 
              Vivamus tristique felis leo, vitae laoreet sapien eleifend vitae. Etiam varius sollicitudin tincidunt');
            $blog->setShortDescription('Lorem ipsum description');
            $blog->setUser($user);
            $blog->setCategory($cat); 
           
            $manager->persist($blog);
        }
        $manager->flush();
    }
}
