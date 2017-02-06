<?php
  require_once('../private/initialize.php');
  error_reporting(E_ALL);
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 1);
  error_reporting(-1);
  // Set default values for all variables the page needs.

  // if this is a POST request, process the form
  // Hint: private/functions.php can help

  if(is_post_request()) {
    // is a POST request

// Confirm that POST values are present before accessing them.
  $first_name = h($_POST['first_name']);
  $last_name = h($_POST['last_name']);
  $email = sanitize_email($_POST['email']);
  $username = h($_POST['username']);
  $created_at = date("Y-m-d H:i:s");
    // Perform Validations
    // Hint: Write these in private/validation_functions.php


      $errors = [];
      if (is_blank($_POST['first_name'])) {
        $errors[] = "First name cannot be blank.";
      } elseif (!has_length($_POST['first_name'], ['min' => 2, 'max' => 20])) {
        $errors[] = "First name must be between 2 and 20 characters.";
      }
      if (is_blank($_POST['last_name'])) {
        $errors[] = "Last name cannot be blank.";
      } elseif (!has_length($_POST['last_name'], ['min' => 2, 'max' => 30])) {
        $errors[] = "Last name must be between 2 and 30 characters.";
      }
      if (is_blank($_POST['email'])) {
        $errors[] = "Email cannot be blank.";
      }

      if (!has_valid_email_format($_POST['email'])) {
        $errors[] = "Invalid email format";
      }

      if (is_blank($_POST['username'])) {
        $errors[] = "Username cannot be blank.";
      } elseif (!has_length($_POST['username'], ['min' => 8, 'max' => 20])) {
        $errors[] = "Username must be between 8 and 20 characters.";
      }

      // Bonus 1
        if (!preg_match("/^[a-zA-Z\s\W]*$/",$_POST['first_name'])) {
          $errors[] = "Only letters, white space and symbols allowed for first name";
        }

        if (!preg_match("/^[a-zA-Z\s\W]*$/",$_POST['last_name'])) {
          $errors[] = "Only letters, white space and symbols allowed for last name";
        }

        if (!preg_match("/^[a-zA-Z\d\W]*$/",$_POST['username'])) {
          $errors[] = "Only letters,numbers and symbols allowed for last name";
        }

        if (!preg_match("/^[@.a-zA-Z\d]*$/",$_POST['email'])) {
          $errors[] = "Only letters,numbers and @ symbol allowed for last name";
        }


  //Bonus 2
  $sql = "SELECT * FROM users WHERE username='$username'";
  $result = db_query($db, $sql);

  $row_cnt = mysqli_num_rows($result);
  if($row_cnt > 0) {
    $errors[] = "This username already exists. Please choose another one!";
  }

    // if there were no errors, submit data to database

    if(empty($errors))
    {
        // Write SQL INSERT statement
        // $sql = "";
        $sql = "INSERT INTO users(first_name, last_name, email, username, created_at) VALUES ('$first_name', '$last_name', '$email', '$username', '$created_at')";
        // For INSERT statments, $result is just true/false
         $result = db_query($db, $sql);
               if($result) {
               db_close($db);

            //   TODO redirect user to success page

               redirect_to("registration_success.php");
               exit;
              }
              else {
               // The SQL INSERT statement failed.
               // Just show the error, not the form
               echo db_error($db);
               db_close($db);
              exit;
             }
     }
    } // end of post request
?>

<?php $page_title = 'Register'; ?>
<?php include(SHARED_PATH . '/header.php'); ?>

<div id="main-content">
  <h1>Register</h1>
  <p>Register to become a Globitek Partner.</p>
  <!-- beginning of form -->
      <form method="post" action="register.php">

 				<!-- end of form -->

  <?php
    // TODO: display any form errors here
    // Hint: private/functions.php can help
    if(isset($errors))
    {
       echo display_errors($errors);;
    }

  ?>

  <!-- TODO: HTML form goes here -->
  <label for="first_name">First Name:</label><br />
  <input type="text" id="first_name" name="first_name" value="<?php if(isset($first_name)) echo $first_name; ?>" /><br />

  <label for="last_name">Last Name:</label><br />
  <input type="text" id="last_name" name="last_name" value="<?php if(isset($last_name)) echo $last_name; ?>" /><br />

  <label for="email">Email:</label><br />
  <input type="text" id="email" name="email" value="<?php if(isset($email)) echo $email; ?>" /><br />

  <label for="last_name">Username:</label><br />
  <input type="text" id="username" name="username" value="<?php if(isset($username)) echo $username; ?>" /><br />
  <br />

  <input type="submit" name="submit" value="Submit" />

</form>


</div>

<?php include(SHARED_PATH . '/footer.php'); ?>
