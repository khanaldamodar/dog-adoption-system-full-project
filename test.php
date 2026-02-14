<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Requests</title>
    <!-- Link to FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .adoption-status {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .request-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: box-shadow 0.3s ease;
        }

        .request-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .request-info {
            display: flex;
            align-items: center;
        }

        .dog-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid #ddd;
        }

        .status {
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.9em;
        }

        .status.approved {
            color: #2ecc71;
            background: #e9f7ef;
        }

        .status.pending {
            color: #f1c40f;
            background: #fef9e7;
        }

        .view-details {
            background: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s ease;
        }

        .view-details:hover {
            background: #2980b9;
        }

        .view-details i {
            font-size: 1.2em;
        }

        .no-requests {
            text-align: center;
            color: #888;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <section class="adoption-status">
        <h2>Adoption Requests</h2>
        <?php if (isset($adoption_requests) && count($adoption_requests) > 0): ?>
            <?php foreach ($adoption_requests as $request): ?>
                <div class="request-card">
                    <div class="request-info">
                        <img src="<?php echo file_exists('uploads/' . $request['dog_image']) 
                            ? 'uploads/' . htmlspecialchars($request['dog_image']) 
                            : 'uploads/default-dog.png'; ?>" 
                            alt="Image of <?php echo htmlspecialchars($request['dog_name']); ?>" class="dog-image">
                        <div>
                            <h3><?php echo htmlspecialchars($request['dog_name']); ?></h3>
                            <p class="status <?php echo strtolower($request['status']); ?>">
                                <i class="fas fa-<?php echo ($request['status'] === 'Approved') ? 'check-circle' : 'clock'; ?>"></i>
                                <?php echo htmlspecialchars($request['status']); ?>
                            </p>
                        </div>
                    </div>
                    <button class="view-details">
                        <i class="fas fa-eye"></i> View Details
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-requests">No adoption requests found.</p>
        <?php endif; ?>
    </section>
</body>
</html>
