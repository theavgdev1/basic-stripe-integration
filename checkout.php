<?php
require_once 'config.php';
require_once 'stripe-php-10.3.0/init.php';

$stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

$cartArray = [
    'price_data' => [
        'currency' => 'usd',
        'product_data' => [
            'name' => 'Tip (not taxed)',
        ],
        'unit_amount' => 5 * 100,  //  convert to cents
    ],
    'quantity' => 1,
];

echo "<pre>";
var_dump($_SERVER);
echo "</pre>";

$checkoutSession = $stripe->checkout->sessions->create([
    'line_items' => $cartArray,
    'mode' => 'payment',
    'success_url' => $_SERVER['HTTP_HOST'].'/checkout-success.php?provider_session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $_SERVER['HTTP_HOST'].'/checkout.php?provider_session_id={CHECKOUT_SESSION_ID}'
]);

// provider_session_id
$checkout_session->id;

// Send user to Stripe
header('Content-Type: application/json');
header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
exit;