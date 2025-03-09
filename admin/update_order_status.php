<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="adminstyles.css">
</head>
<body>
    <header>
        <div class="logo">🌿 Pharma Admin</div>
        <div class="admin-info">
            <span>Welcome, Admin</span>
            <button class="logout-btn" onclick="handleLogout()">Logout</button>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <button class="toggle-btn">☰</button>
            <nav>
                <ul>
                    <li><a href="#" class="active">🏠 Dashboard</a></li>
                    <li><a href="#order">🛒 Order Management</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <section class="table-section" id="order">
                <h2>Order Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'db_connect.php';
                        $query = "SELECT * FROM orders";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['total_amount']) . "</td>";
                                echo "<td><button onclick='approveOrder(" . $row['id'] . ")'>Approve</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No orders found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <footer>
        <p>© 2025 Pharma Companion. All rights reserved.</p>
    </footer>

    <script>
        function approveOrder(orderId) {
            if (!confirm("Are you sure you want to approve this order?")) {
                return;
            }

            fetch('admin_approve_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'order_id=' + orderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while approving the order.');
            });
        }
    </script>
</body>
</html>