<?php

include 'components/connect.php';

    if(isset($_COOKIE)){
        $user_id = $_COOKIE['user_id'];
    }else{
        $user_id = '';
    }

?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'components/user_header.php';?>
    
    <section class="form">
        <form action="" method="post">
            <h1 class="heading">Rate us</h1>
        </form>
    </section>




    <?php include 'components/footer.php';?>
</body>
</html>