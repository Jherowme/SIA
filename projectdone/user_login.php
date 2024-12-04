<?php
include 'components/connect.php';

session_start();

// Logout logic
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session
    header("Location: user_login.php");  // Redirect to login page
    exit();
}

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Check if user exists in database
    $select_user = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $_SESSION['user_id'] = $row['id'];
        header('location:user_login.php');
    } else {
        $message[] = 'Incorrect Username or Password!';
    }
}

// Google OAuth integration
require_once 'vendor/autoload.php';

// Initialize Google Client
$clientID = '1033058283452-s9r1906ofsl9a1csopli8rnoq1dvtg50.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/projectdone/user_login.php';

// Create Google Client instance
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Handle OAuth callback
if (isset($_GET['code'])) {
    try {
        // Fetch the access token
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($token['access_token'])) {
            // Set access token
            $client->setAccessToken($token['access_token']);

            // Get user profile info
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $google_email =  $google_account_info->email;
            $google_name =  $google_account_info->name;

            // Check if user exists in the database
            $select_google_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $select_google_user->execute([$google_email]);
            $user_row = $select_google_user->fetch(PDO::FETCH_ASSOC);

            if ($select_google_user->rowCount() > 0) {
                // If user exists, log them in
                $_SESSION['user_id'] = $user_row['id'];
                header('location:index.php');
            } else {
                // If user does not exist, register them
                $insert_user = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
                $insert_user->execute([$google_name, $google_email]);

                // Fetch the newly created user details
                $select_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $select_user->execute([$google_email]);
                $new_user = $select_user->fetch(PDO::FETCH_ASSOC);

                // Log the new user in
                $_SESSION['user_id'] = $new_user['id'];
                header('location:index.php');
            }
        } else {
            echo 'Error retrieving access token';
        }
    } catch (Exception $e) {
        echo 'OAuth Error: ' . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      /* Custom style for Google login button */
      .google-login a {
         display: block;
         background-color: #db4437;
         color: white;
         text-align: center;
         padding: 12px 0;
         border-radius: 5px;
         font-size: 16px;
         text-decoration: none;
         margin-top: 10px;
         width: 100%;
      }

      .google-login a:hover {
         background-color: #c1351d;
      }

      /* Form container */
      .form-container {
         width: 100%;
         max-width: 400px;
         margin: 0 auto;
         padding: 20px;
         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
         background-color: #fff;
      }

      .form-container h3 {
         text-align: center;
         margin-bottom: 20px;
      }

      .form-container .box {
         width: 100%;
         padding: 12px;
         margin: 8px 0;
         border: 1px solid #ddd;
         border-radius: 5px;
      }

      .form-container .btn {
         width: 100%;
         padding: 12px;
         background-color: #4CAF50;
         color: white;
         border: none;
         border-radius: 5px;
         cursor: pointer;
      }

      .form-container .btn:hover {
         background-color: #45a049;
      }

      .form-container p {
         text-align: center;
         margin-top: 20px;
      }
   </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Login Now</h3>
      
      <input type="email" name="email" required placeholder="Enter your Email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your Password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <div class="google-login">
         <a href="<?php echo $client->createAuthUrl(); ?>" class="btn">Login with Google</a>
      </div>

      <input type="submit" value="login now" class="btn" name="submit">
      <p>Don't have an account?</p>
      <p><a href="user_register.php" style="color: blue;">Register Now</a>.</p>

   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
