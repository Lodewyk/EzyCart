<?php

/**
 * Class EzyCart
 *
 * Contains functionality for keeping control of the items in the cart
 */
class EzyCart
{
    /**
     * EzyCart constructor.
     *
     * Starts the session
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Initializes cart if it has not been set yet
     *
     * @param array $received - array of item data
     */
    public function initCart($received)
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        // init item
        if (!isset($_SESSION['cart'][$received[0]])) {
            $_SESSION['cart'][$received[0]] = [
                'name' => $received[0],
                'price' => $received[1],
                'quantity' => 0,
                'total' => 0,
            ];
        }
    }

    /**
     * Adds a specified item to the cart
     *
     * @param string $value - details of item to add to cart
     */
    public function in($value)
    {
        $received = explode(':', $value);
        // init cart
        $this->initCart($received);
        $quantity = $_SESSION['cart'][$received[0]]['quantity'] + 1;
        $totalInt = $quantity * $received[1];
        $totalFloat = floatval($totalInt);
        $total = number_format($totalFloat, 2, '.', '');

        $_SESSION['cart'][$received[0]]['quantity'] = $quantity;
        $_SESSION['cart'][$received[0]]['total'] = $total;
        print_r(json_encode($_SESSION));
    }

    /**
     * Removes a specified item from the cart
     *
     * @param string $value - details of item to remove from cart
     */
    public function out($value)
    {
        $received = explode(':', $value);
        // assume cart has been set
        $quantity = $_SESSION['cart'][$received[0]]['quantity'] - 1;
        $totalInt = $quantity * $received[1];
        $totalFloat = floatval($totalInt);
        $total = number_format($totalFloat, 2, '.', '');
        // remove item from cart if quantity is 0
        if ($quantity == 0) {
            unset($_SESSION['cart'][$received[0]]);
            print_r(json_encode($_SESSION));
            die();
        }
        $_SESSION['cart'][$received[0]]['quantity'] = $quantity;
        $_SESSION['cart'][$received[0]]['total'] = $total;
        print_r(json_encode($_SESSION));
    }

    /**
     * Calculates the cart total
     */
    public function total()
    {
        $total = 0;
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item => $details) {
                $itemTotal = $details['total'];
                $total = $total + $itemTotal;
                $total = round($total, 2);
            }
        }
        print(json_encode(['total' => $total]));
    }
}