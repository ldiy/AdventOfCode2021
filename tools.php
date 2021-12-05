<?php

function lines($filename) : bool|array
{
    return file($filename, FILE_IGNORE_NEW_LINES);
}

function regex_groups($regex, $string) : array
{
    preg_match($regex, $string, $groups);
    array_shift($groups);
    return $groups;
}