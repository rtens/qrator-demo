<?php

if (!file_exists('build/composer.phar')) {
    @mkdir('build');

    echo "Downloading Composer installer..." . PHP_EOL;
    file_put_contents("build/install_composer.php", file_get_contents('http://getcomposer.org/installer'));

    echo "Installing composer.phar" . PHP_EOL;
    system("php build/install_composer.php --install-dir build");

    system('cp src/blog/blog.json.dist src/blog/blog.json');
}

system("php build/composer.phar install");

require_once __DIR__ . "/bootstrap.php";