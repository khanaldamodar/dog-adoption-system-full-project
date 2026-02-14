
<?php
// Database Configuration
$host = 'localhost';
$dbname = 'dog-adoption-project';
$username = 'root';
$password = '';

// Connect to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $amount = floatval($_POST['amount']);
    $card_number = htmlspecialchars($_POST['card_number']);
    $expiry_date = htmlspecialchars($_POST['expiry_date']);
    $cvv = htmlspecialchars($_POST['cvv']);
    $pin = htmlspecialchars($_POST['pin']);

    // Validate card details against the database
    try {
        $stmt = $conn->prepare("SELECT * FROM cards WHERE card_number = :card_number AND expiry_date = :expiry_date AND cvv = :cvv AND pin = :pin");
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':expiry_date', $expiry_date);
        $stmt->bindParam(':cvv', $cvv);
        $stmt->bindParam(':pin', $pin);
        $stmt->execute();
        $card = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$card) {
            die("Invalid card details or PIN. Please try again.");
        }

        // Simulate payment processing
        $transaction_id = uniqid('txn_', true); // Generate a unique transaction ID

        // Insert donation into the database with foreign key reference to cards table
        $stmt = $conn->prepare("INSERT INTO donations (donor_name, donor_email, amount, card_number, transaction_id) VALUES (:name, :email, :amount, :card_number, :transaction_id)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':card_number', $card_number);
        $stmt->bindParam(':transaction_id', $transaction_id);
        $stmt->execute();

        // Redirect to receipt page
        header('Location: donation receipt.php?transaction_id=' . $transaction_id);
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Adoption Donation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your unchanged CSS here */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f7f8fa;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 700px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
            border: 2px solid #ff6f61;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            text-align: left;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #ff6f61;
            outline: none;
            box-shadow: 0 0 8px rgba(255, 111, 97, 0.5);
        }

        button {
            background: #ff6f61;
            color: #fff;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            background: #ff4a3d;
            box-shadow: 0 4px 10px rgba(255, 111, 97, 0.4);
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group div {
            flex: 1;
            min-width: calc(50% - 20px);
            position: relative;
        }

        input, select {
            box-sizing: border-box;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .form-group {
                flex-direction: column;
                gap: 15px;
            }

            input, select {
                margin-bottom: 15px;
            }
        }

        .logo {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
  

    <div class="container">
        <img src="https://via.placeholder.com/100" alt="Ado~Pups Logo" class="logo">
        <h1>Support Dog Adoption 🐾</h1>
        <p>Your donation helps us save more dogs and find them loving homes.</p>

        <!-- Display success or error message -->
        <?php if (!empty($successMessage)): ?>
            <div style="color: green; margin-bottom: 20px;"><?php echo $successMessage; ?></div>
        <?php elseif (!empty($errorMessage)): ?>
            <div style="color: red; margin-bottom: 20px;"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Donation Form -->
        <form id="donation-form" method="POST">
            <div class="form-group">
                <div>
                    <label for="name"><i class="fas fa-user icon"></i> Your Name:</label>
                    <input type="text" id="name" name="name" required placeholder="John Doe" value="<?php echo isset($name) ? $name : ''; ?>">
                </div>
                <div>
                    <label for="email"><i class="fas fa-envelope icon"></i> Your Email:</label>
                    <input type="email" id="email" name="email" required placeholder="john@example.com" value="<?php echo isset($email) ? $email : ''; ?>">
                </div>
            </div>

            <label for="amount"><i class="fas fa-dollar-sign icon"></i> Donation Amount ($):</label>
            <input type="number" id="amount" name="amount" min="1" required placeholder="50" value="<?php echo isset($amount) ? $amount : ''; ?>">

            <label for="payment-method">Select Payment Method:</label>
            <select id="payment-method" name="payment-method" required>
                <option value="">-- Select Payment Method --</option>
                <option value="card" <?php echo isset($paymentMethod) && $paymentMethod === 'card' ? 'selected' : ''; ?>>Credit/Debit Card</option>
            </select>

            <div id="card-details" class="<?php echo isset($paymentMethod) && $paymentMethod === 'card' ? '' : 'hidden'; ?>">
                <div class="form-group">
                    <div>
                        <label for="card_number"><i class="fas fa-credit-card icon"></i> Card Number:</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" value="<?php echo isset($cardNumber) ? $cardNumber : ''; ?>">
                    </div>
                    <div>
                        <label for="expiry_date"><i class="fas fa-calendar-alt icon"></i> Expiry Date:</label>
                        <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" value="<?php echo isset($expiryDate) ? $expiryDate : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="cvv"><i class="fas fa-lock icon"></i> CVV:</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" value="<?php echo isset($cvv) ? $cvv : ''; ?>">
                    </div>
                    <div>
                        <label for="pin"><i class="fas fa-key icon"></i> PIN:</label>
                        <input type="password" id="pin" name="pin" placeholder="4-digit PIN" value="<?php echo isset($pin) ? $pin : ''; ?>">
                    </div>
                </div>
            </div>

            <button type="submit"><i class="fas fa-heart icon"></i> Donate Now</button>
        </form>
    </div>

    <script>
        const paymentMethodSelect = document.getElementById('payment-method');
        const cardDetails = document.getElementById('card-details');

        paymentMethodSelect.addEventListener('change', function () {
            if (this.value === 'card') {
                cardDetails.classList.remove('hidden');
            } else {
                cardDetails.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
