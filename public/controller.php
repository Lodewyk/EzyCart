<?php

include_once 'EzyCart.php';

/**
 * This file acts as a controller for adding/removing items from the cart
 */

$ezycart = new EzyCart();

if (!empty($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'add':
            $ezycart->in($_REQUEST['value']);
            break;

        case 'remove':
            $ezycart->out($_REQUEST['value']);
            break;

        case 'total':
            $ezycart->total();
            break;
    }
}

?>