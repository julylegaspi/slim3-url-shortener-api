<?php

$app->get('/','HomeController:index')->setName('homepage');

$app->post('/api/generate','HomeController:generate')->setName('generate.post');

$app->get('/{code}','HomeController:code')->setName('code.get');