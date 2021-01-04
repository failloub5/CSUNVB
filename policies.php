<?php
/**
 * File : policies.php
 * Author : X. Carrel
 * Created : 18.12.20
 * Modified last :
 **/

// the array is a whitelist of actions.
// the user logged in has an 'admin' field, numeric:
// 0: regular user
// 1: admin
// A user is allowed to perform 'action' if and only if $policies[admin][action] is set

$policies = [
    0 => [
        "editsheet" => true,
    ],
    1 => [
        "editsheet" => true,
        "closesheet" => true,
        "createsheet" => true
    ]
];
