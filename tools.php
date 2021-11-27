<?php

function lines($filename) {
    return file($filename, FILE_IGNORE_NEW_LINES);
}

