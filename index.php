<?php
    require 'vendor/autoload.php';
    $faker = Faker\Factory::create();
    echo $faker->name;

    $app->post('/like/add', function ($request, $response) use ($container) {
        $likeAction = $container->get(AddLike::class);
        return $likeAction->handle($request, $response);
    });
?>