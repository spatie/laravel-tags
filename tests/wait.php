<?php

$start = time();

while (true) {
    try {
        new PDO('mysql:host=127.0.0.1;dbname=laravel_tags', 'username', 'password');
        fwrite(STDOUT, 'Docker container started!'.PHP_EOL);
        exit(0);
    } catch (PDOException $exception) {
        $elapsed = time() - $start;

        if ($elapsed > 30) {
            fwrite(STDERR, 'Docker container did not start in time...'.PHP_EOL);
            exit(1);
        }

        fwrite(STDOUT, 'Waiting for container to start...'.PHP_EOL);
        sleep(1);
    }
}
