<?php

function generate_csrf_token()
{
    $_SESSION['csrf_token'] = md5(uniqid(mt_rand(), true));
}

function check_csrf_token($csrf_token): bool
{
    return $csrf_token == $_SESSION['csrf_token'];
}

function generate_csrf_field()
{
    echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}
