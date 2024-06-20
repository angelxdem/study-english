<?php
session_start();
include 'db.php';

// Перевірка, чи користувач має права доступу до адмін-панелі
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Отримання списку користувачів
try {
    $users_stmt = $conn->query("SELECT * FROM users WHERE username != 'admin'");
    $users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching users: " . $e->getMessage();
}

// Отримання списку контактів
try {
    $contacts_stmt = $conn->query("SELECT * FROM contacts");
    $contacts = $contacts_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching contacts: " . $e->getMessage();
}

// Функція для видалення користувача
function deleteUser($conn, $id)
{
    try {
        $stmt = $conn->prepare("DELETE FROM test_results WHERE user_id = ?");
        $stmt->execute([$id]);

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        return true;
    } catch (PDOException $e) {
        return "Error deleting user: " . $e->getMessage();
    }
}

// Функція для видалення контакту
function deleteContact($conn, $id)
{
    try {
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return "Error deleting contact: " . $e->getMessage();
    }
}

// Функція для оновлення статусу контакту
function updateContactStatus($conn, $id, $status)
{
    try {
        $stmt = $conn->prepare("UPDATE contacts SET is_processed = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        return true;
    } catch (PDOException $e) {
        return "Error updating contact status: " . $e->getMessage();
    }
}

// Обробка запиту на видалення користувача
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteResult = deleteUser($conn, $_POST['delete_id']);
    header("Location: admin.php"); // Перенаправлення на ту ж сторінку після видалення
    exit();
}

// Обробка запиту на видалення контакту
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_contact_id'])) {
    $deleteResult = deleteContact($conn, $_POST['delete_contact_id']);
    header("Location: admin.php"); // Перенаправлення на ту ж сторінку після видалення
    exit();
}

// Обробка запиту на оновлення статусу контакту
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_contact_id'])) {
    $status = isset($_POST['is_processed']) ? 1 : 0;
    $updateResult = updateContactStatus($conn, $_POST['update_contact_id'], $status);
    header("Location: admin.php"); // Перенаправлення на ту ж сторінку після оновлення
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            margin-top: 100px;
            max-width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        h3{
            margin-top: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-delete {
            background-color: #ff0000;
            color: #fff;
        }

        .btn-edit {
            background-color: #4CAF50;
            color: #fff;
        }

        /* Error message */
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        /* Processed row style */
        .processed {
            background-color: #d4edda;
        }
    </style>
</head>

<body>
    <?php include 'admin_menu.php'; ?>

    <div class="container">
        <h2>Admin Panel</h2>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php else : ?>
            <h3>Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Age</th>
                        <th>English Level</th>
                        <th>About</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['phone']; ?></td>
                            <td><?php echo $user['age']; ?></td>
                            <td><?php echo $user['english_level']; ?></td>
                            <td><?php echo $user['about_me']; ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Contacts</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Created At</th>
                        <th>Processed</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact) : ?>
                        <tr class="<?php echo $contact['is_processed'] ? 'processed' : ''; ?>">
                            <td><?php echo $contact['id']; ?></td>
                            <td><?php echo $contact['name']; ?></td>
                            <td><?php echo $contact['email']; ?></td>
                            <td><?php echo $contact['message']; ?></td>
                            <td><?php echo $contact['created_at']; ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="update_contact_id" value="<?php echo $contact['id']; ?>">
                                    <input type="checkbox" name="is_processed" <?php echo $contact['is_processed'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="delete_contact_id" value="<?php echo $contact['id']; ?>">
                                    <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this contact?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
