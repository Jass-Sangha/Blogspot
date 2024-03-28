<?php
include('../../master/conn.php');
include('../../components/mail.php');

if(isset($_SESSION['logged_in'])){
    if($_SESSION['logged_in'] == 'true'){
        header('Location: http://localhost/Github/Blogspot/index.php');
    }
}

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token= uniqid();
    $link = 'http://localhost/Github/Blogspot/user/auth/verify.php?token='.$token.'&email='.$email;
    $thirtyMinutes = date("Y/m/d H:i:s", strtotime("+30 minutes"));

    $result = $conn->query("Select * from users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        header('Location: http://localhost/Github/Blogspot/user/auth/regenerate.php?name='.$name.'&email='.$email.'');
    } else {
        $sql = "INSERT INTO users (name, email, password, token, token_valid_till, created_at) VALUES ('$name', '$email', '$password','$token','$thirtyMinutes',now())";

        if ($conn->query($sql) === TRUE) {
            sendMail($email, $name, $link);
             header('Location: http://localhost/Github/Blogspot/user/auth/regenerate.php?name='.$name.'&email='.$email.'');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/components.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>BlogSpot | Login page</title>
</head>
<body>
    <section id="auth">
        <form action="" method="POST">
            <div class="form-input">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" placeholder="Name" required/>
            </div>
            <div class="form-input">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Email id" required/>
            </div>
            <div class="form-input">
                <label for="password">Password</label>
                <input id="password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,16}" title="Please enter at-least 8 characters with one lower and upper letter"  type="text" name="password" placeholder="password" required/>
            </div>
            <button class="__btn" name="submit" type="submit">Submit</button>

        </form>
    </section>
</body>
</html>