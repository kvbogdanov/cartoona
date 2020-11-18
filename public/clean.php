<?php

    $options = getopt("f:");
    $strings = file_get_contents($options['f']);
    $strings = strip_tags($strings);
    $strings = explode(PHP_EOL, $strings);
    $output = [];
    var_dump($strings);
    foreach ($strings as $string)
    {
        if(!empty($string) && !strpos($string, '-->') && !is_numeric($string))
            $output[] = $string;
    }
    file_put_contents('output.txt', implode(PHP_EOL, $output));
