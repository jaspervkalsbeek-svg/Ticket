<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login'; 

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inloggen</title>
    <link rel="stylesheet" href="login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <div class="container">
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Inloggen</h2>
                <?=showError($errors['login']);?>
                <input type="email" name="email" placeholder="email" required>

                <input type="password" name="password" placeholder="password" required>

                <button type="submit" name="login">login</button>

                <p>heb je geen account? <a href="#" id="show-register-form">Registreer</a></p>
            </form>
        </div>


        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h2>Registreer</h2>
                <?=showError($errors['register']);?>

                <input type="text" name="name" placeholder="name" required>

                <input type="email" name="email" placeholder="email" required>
                
                <input type="password" name="password" placeholder="password" required>
                <select name="role" required>
                    <option value="">Select role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="register">Registreer</button>
                

                <p>heb je al een account? <a href="#" id="show-login-form">inloggen</a></p>
            </form>
        </div>

    </div>


    <script src="login.js"></script>
</body>

</html>