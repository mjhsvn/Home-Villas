<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING); 
   $c_pass = sha1($_POST['c_pass']);
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);   

   $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_users->execute([$email]);

   if($select_users->rowCount() > 0){
      $warning_msg[] = 'email already taken!';
   }else{
      if($pass != $c_pass){
         $warning_msg[] = 'Password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, number, email, password) VALUES(?,?,?,?,?)");
         $insert_user->execute([$id, $name, $number, $email, $c_pass]);
         
         if($insert_user){
            $verify_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
            $verify_users->execute([$email, $pass]);
            $row = $verify_users->fetch(PDO::FETCH_ASSOC);
         
            if($verify_users->rowCount() > 0){
               setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
               header('location:home.php');
            }else{
               $error_msg[] = 'something went wrong!';
            }
         }

      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="icon" href="favicon.ico">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>


<section class="form-container">
        <form action="" method="post">
            <h3>create an account!</h3>
            <input type="tel" name="name" required maxlength="50" placeholder="enter your name" class="box">
            <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
            <input type="number" name="number" required min="0" max="99999999999" maxlength="15" placeholder="enter your number" class="box">
            <input type="password" id="password" name="pass" required maxlength="20" placeholder="enter your password" class="box">
            <input type="password"  id="c_password" name="c_pass" required maxlength="20" placeholder="confirm your password" class="box">
            <div id="password-strength"></div>
            <p>already have an account? <a href="login.php">login now</a></p>
            <input type="submit" value="register now" name="submit" class="btn">
        </form>
    </section>

   

    <!--password strength detector--> 

    <script>
    const passwordInput = document.getElementById('password');
    const cPasswordInput = document.getElementById('c_password');
    const passwordStrengthDiv = document.getElementById('password-strength');

    passwordInput.addEventListener('input', updatePasswordStrength);
    cPasswordInput.addEventListener('input', updatePasswordStrength);

    function updatePasswordStrength() {
        const password = passwordInput.value;
        if (password === '') {
            passwordStrengthDiv.innerText = '';
            passwordStrengthDiv.className = '';
            return;
        }

        const strength = calculatePasswordStrength(password);

        let strengthText = '';
        let strengthClass = '';
        if (strength === 'Weak') {
            strengthText = 'Weak';
            strengthClass = 'weak';
        } else if (strength === 'Moderate') {
            strengthText = 'Moderate';
            strengthClass = 'moderate';
        } else if (strength === 'Strong') {
            strengthText = 'Strong';
            strengthClass = 'strong';
        }

        passwordStrengthDiv.innerText = `Password Strength: ${strengthText}`;
        passwordStrengthDiv.className = strengthClass;
    }

    function calculatePasswordStrength(password) {
        const length = password.length;
        if (length < 8) {
            return 'Weak';
        } else if (length < 12) {
            return 'Moderate';
        } else {
            return 'Strong';
        }
    }
</script>






<!--js file and php includes-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>
<script src="js/main.js"></script>

</body>
</html>