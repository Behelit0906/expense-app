<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>Sign Up</h1>

    <?php if(isset($_SESSION['validateErrors'])): ?>
        <?php $errors = $_SESSION['validateErrors'] ?>
        <?php foreach($errors as $error): ?>
            <?= $error ?> <br>
        <?php endforeach; ?>

    <?php endif; ?>

    <form action="signup" method="post">
        <label for="namr">Name </label><input type="text" name="name" id="name"><br>
        <label for="email">Email </label><input type="text" name="email" id="email"><br>
        <label for="password ">Password</label><input type="text" name="password" id="password"><br>
        <label for="password_confirmation">Password </label>
        <input type="text" name="password_confirmation" id="password_confirmation"><br>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>