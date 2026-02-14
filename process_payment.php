<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $amount = floatval($_POST['amount']);
    $card = htmlspecialchars($_POST['card']);
    $expiry = htmlspecialchars($_POST['expiry']);
    $cvv = htmlspecialchars($_POST['cvv']);

    // Simulate payment processing
    $transaction_id = uniqid('txn_', true);
    $date = date('Y-m-d H:i:s');

    // Redirect to receipt page with details
    header('Location: receipt.php?name=' . urlencode($name) .
        '&email=' . urlencode($email) .
        '&amount=' . urlencode($amount) .
        '&transaction_id=' . urlencode($transaction_id) .
        '&date=' . urlencode($date));
    exit();
}
?>