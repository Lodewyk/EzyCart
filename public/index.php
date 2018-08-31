<?php

$items = [
    ['name' => 'Sledgehammer', 'price' => 125.75],
    ['name' => 'Axe', 'price' => 190.5],
    ['name' => 'Bandsaw', 'price' => 562.13],
    ['name' => 'Chisel', 'price' => 12.9],
    ['name' => 'Hacksaw', 'price' => 18.45],
];

include_once 'EzyCart.php';

$ezycart = new EzyCart();

?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <style type="text/css">
            .shopping-list {
                width: 50%;
                height: 100%;
                float: left;
            }
            .cart-container {
                width: 50%;
                height: 100%;
                float: left;
            }
        </style>
    </head>
    <body>
        <div class="shopping-list">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                </tr>
                <?php foreach ($items as $item){ ?>
                    <tr>
                        <td><?php echo $item['name'] ?></td>
                        <td><?php echo $item['price'] ?></td>
                        <td>
                            <button class="add" value="<?php echo $item['name'] . ':' . $item['price'] ?>">
                                Add
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="cart-container">
            <table class="cart-table">
                <tr>
                    <th colspan="5">Your cart</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                <tbody id="cart-items">
                    <?php if (isset($_SESSION) && !empty($_SESSION)) { ?>
                        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { ?>
                            <?php foreach ($_SESSION['cart'] as $key => $count) { ?>
                                <?php $item = $_SESSION['cart'][$key] ?>
                                <tr>
                                    <td><?php echo $item['name'] ?></td>
                                    <td><?php echo $item['price'] ?></td>
                                    <td><?php echo $item['quantity'] ?></td>
                                    <td><?php echo $item['total'] ?></td>
                                    <td>
                                        <button class="remove" value="<?php echo $item['name'] . ':' . $item['price'] ?>">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>
    <footer>
        <script>
            /**
             * Builds rows of cart table content
             *
             * @param json data - json object of a single cart row data
             *
             * @returns {string|string}
             */
            var makeRow = function(data) {
                var row = "<tr>";
                row = row + "<td>" + data.name + "</td>";
                row = row + "<td>" + data.price + "</td>";
                row = row + "<td>" + data.quantity + "</td>";
                row = row + "<td>" + data.total + "</td>";
                var value = data.name + ":" + data.price;
                row = row + "<td><button class='remove' value='" + value + "'>Remove</button></td>"
                row = row + "</tr>";
                return row;
            };

            /**
             * Redraws the cart table
             *
             * @param json data - complete json object of cart table data
             */
            var redraw = function (data) {
                var session = $.parseJSON(data);
                var cart = session.cart;
                var table = "";
                $.each(cart, function (index, value) {
                    table = table + makeRow(value);
                });
                $("#cart-items").html(table);
                $(".remove").unbind();
                bindRemove();
            };

            /**
             * Calculate and displays cart total
             */
            var calculateTotal = function () {
                $.ajax({
                    url: 'controller.php',
                    type: 'GET',
                    data:{
                        action : 'total'
                    },
                    success: function (data) {
                        var totalData = $.parseJSON(data)
                        var total = "<tr>";
                        total = total + "<td colspan='3'>Total</td>";
                        total = total + "<td>" + totalData.total + "</td>";
                        total = total + "<td></td>"
                        total = total + "</tr>";
                        $("#cart-items").append(total);
                    }
                })
            }

            /**
             * Binds the remove listener to remove buttons.
             */
            var bindRemove = function () {
                $(".remove").bind("click", function () {
                    var value = $(this).val();
                    $.ajax({
                        url: 'controller.php',
                        type: 'GET',
                        data:{
                            action : 'remove',
                            value : value
                        },
                        success: function (data) {
                            redraw(data);
                        }
                    })
                });
                calculateTotal();
            };

            $(document).ready(function () {
                $(".add").click(function (e) {
                    var value = $(this).val();
                    $.ajax({
                        url: 'controller.php',
                        type: 'GET',
                        data: {
                            action : 'add',
                            value : value
                        },
                        success: function (data) {
                            redraw(data);
                        }
                    });
                });
                bindRemove();
            });
        </script>
    </footer>
</html>
