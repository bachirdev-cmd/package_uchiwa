<?php

function dd( $data) : void {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function file_reader($file_path = '') : array {
    return file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?? [];
}