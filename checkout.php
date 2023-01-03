<?php
require_once 'config.php';
require_once 'stripe-php-10.3.0/init.php';

$stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

$cartArray = [
    [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Fried Rice',
            ],
            'unit_amount' => 9.99 * 100,  //  convert to cents
            'tax_behavior' => 'exclusive'
        ],
        'quantity' => 1,
        'tax_rates' => [STRIPE_TAX_RATE_ID]
    ],
    [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Fried Noodle',
            ],
            'unit_amount' => 11.99 * 100,  //  convert to cents
            'tax_behavior' => 'exclusive'
        ],
        'quantity' => 2,
        'tax_rates' => [STRIPE_TAX_RATE_ID]
    ],
    [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Tip (not taxed)',
            ],
            'unit_amount' => 5 * 100,  //  convert to cents
        ],
        'quantity' => 1,
    ]
];

// echo "<pre>";
// var_dump($_SERVER);
// echo "</pre>";

$checkoutSession = $stripe->checkout->sessions->create([
    'line_items' => $cartArray,
    'mode' => 'payment',
    'success_url' => 'http://localhost/checkout-success.php?provider_session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://localhost/cart.php?provider_session_id={CHECKOUT_SESSION_ID}'
]);

// provider_session_id
$checkoutSession->id;

// Send user to Stripe
header('Content-Type: application/json');
header("HTTP/1.1 303 See Other");
header("Location: " . $checkoutSession->url);
exit;