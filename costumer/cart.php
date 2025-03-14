<?php
session_start();
include 'dbcon.php';
$customerID = $_SESSION['customerID'];

// Initialize an empty array to store cart items
$cart_items = array();

// Fetch cart items from the database

$sql = "SELECT * FROM cart WHERE customerID = $customerID";
$result = $conn->query($sql);
if ($result) {
    // Fetch each row as an associative array
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = array(
            'prodID' => $row['prodID'],
            'prod_name' => $row['prod_name'],
            'prod_price' => $row['prod_price'],
            'quantity' => $row['quantity'],
            'img' => $row ['img']
            // Add other fields as needed
        );
    }
} else {
    echo "Error fetching cart items: " . mysqli_error($mysqli);
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="short icon" href="../costumer/logo.jpg" type="x-icon">
    <title>
        <?php echo "View Cart"; ?>
    </title>
    <link rel="stylesheet" href="../costumer/css/users.css">
    <link rel="stylesheet" href=".../costumer/css/font.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
   
</head>
<style>
.item-details {
    display: flex; /* Ensure items are laid out in a row */
    align-items: center; /* Align items vertically */
}

.item-image {
    flex-shrink: 0; /* Prevent image from shrinking */
    margin-right: 10px; /* Adjust margin as needed */
}

.item-image img {
    max-width: 100%; /* Ensure image fits within container */
    height: auto; /* Maintain aspect ratio */
}
</style>
<body>
   
<?php include 'sidenav.php'; ?>

<main>
    <div class="cart-container">
        <div class="cart-items">
            <h3>My Cart</h3>
            <table>
                <thead>
                    <tr>
                        <th style="text-align: start;">Product</th>
                        <th style="text-align: end;">Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr class="cart-item">
                            <td>
                                <div class="item-details">
                                    <input type="checkbox" class="item-checkbox">
                                    <!-- Assuming you have an image field in your database -->
                                    <div class="item-image">
<img src="<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['prod_name']); ?>">
                                    </div>
                                    <div>
                                        <h3><?php echo htmlspecialchars($item['prod_name']); ?></h3>
                                        <p>₱<?php echo number_format($item['prod_price'], 2); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="quantity-controls">
                                    <button class="decrement">-</button>
                                    <input type="text" value="<?php echo $item['quantity']; ?>" class="quantity">
                                    <button class="increment">+</button>
                                </div>
                            </td>
                            <td>
                                <p class="item-total">₱<?php echo number_format($item['prod_price'] * $item['quantity'], 2); ?></p>
                                <button class="remove-item">X</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="order-summary">
            <h2>Order Summary</h2>
            <?php
            $subtotal = 0;
            foreach ($cart_items as $item) {
                $subtotal += $item['prod_price'] * $item['quantity'];
            }
            ?>
            <p>Sub-total (<?php echo count($cart_items); ?> items): ₱<?php echo number_format($subtotal, 2); ?></p>
            <p>Tax included and shipping calculated at checkout</p>
            <p>Total: ₱<?php echo number_format($subtotal, 2); ?></p>
            <button class="checkout-btn">Check Out</button>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartItems = document.querySelectorAll('.cart-item');

        cartItems.forEach(function(item) {
            const decrementBtn = item.querySelector('.decrement');
            const incrementBtn = item.querySelector('.increment');
            const quantityInput = item.querySelector('.quantity');
            const itemTotal = item.querySelector('.item-total');
            const price = parseFloat(itemTotal.innerText.replace('₱', '').replace(',', ''));

            decrementBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                if (quantity > 1) {
                    quantity--;
                    quantityInput.value = quantity;
                    itemTotal.innerText = `₱${(quantity * price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                }
            });

            incrementBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                quantity++;
                quantityInput.value = quantity;
                itemTotal.innerText = `₱${(quantity * price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            });
        });
    });

    const toggle = document.getElementById('dark-mode-toggle');
    const logo = document.getElementById('logo');

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        toggle.classList.add('rotate');
        if (document.body.classList.contains('dark-mode')) {
            toggle.classList.remove('bi-moon-fill');
            toggle.classList.add('bi-sun-fill');
            logo.src = '../customer/tech-haven-logo2.png';
        } else {
            toggle.classList.remove('bi-sun-fill');
            toggle.classList.add('bi-moon-fill');
            logo.src = '../customer/tech-haven-logo2.png';
        }
        setTimeout(() => {
            toggle.classList.remove('rotate');
        }, 400);
    });
</script>
</body>
</html>