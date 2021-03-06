<?php
//signin.php
include 'connect.php';
?>

<head lang = "en-US">
	<meta charset="utf-8">
	
	<title>Overwatch Forum</title>
	<link rel="stylesheet" href="styles/reset.css" />
	<link rel="stylesheet" href="newHero.css" />
	<link rel="icon" href="images/favicon.png" />
	
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
</head>
<?php 
include 'includes/final-header.php';
?>
<body>
<?php
echo '<h1 class="cat_title" style="font-family: '.'Overwatch'.'; color:#D6D7E6; font-size: 5em; text-align: center;">Sign Up</h1>';


if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    /*the form hasn't been posted yet, display it
      note that the action="" will cause the form to post to the same page it is on */
    echo '<form style="text-align: center; width: 100%;" method="post" action="">
         <h2 class="cat_title" style="font-family: '.'Overwatch'.'; color: orange; font-size: 3em;">Username:</h2> <input type="text" name="user_name" />
        <h2 class="cat_title" style="font-family: '.'Overwatch'.'; color: orange; font-size: 3em;">Password:</h2> <input type="password" name="user_pass">
         <h2 class="cat_title" style="font-family: '.'Overwatch'.'; color: orange; font-size: 3em;">Password again:</h2> <input type="password" name="user_pass_check">
         <h2 class="cat_title" style="font-family: '.'Overwatch'.'; color: orange; font-size: 3em;">Email:</h2> <input type="email" name="user_email">
        <input type="submit" value="Sign up" />
     </form>';
}
else
{
    /* so, the form has been posted, we'll process the data in three steps:
        1.  Check the data
        2.  Let the user refill the wrong fields (if necessary)
        3.  Save the data 
    */
    $errors = array(); /* declare the array for later use */
     
    if(isset($_POST['user_name']))
    {
        //the user name exists
        if(!ctype_alnum($_POST['user_name']))
        {
            $errors[] = 'The username can only contain letters and digits.';
        }
        if(strlen($_POST['user_name']) > 30)
        {
            $errors[] = 'The username cannot be longer than 30 characters.';
        }
    }
    else
    {
        $errors[] = 'The username field must not be empty.';
    }
     
     
    if(isset($_POST['user_pass']))
    {
        if($_POST['user_pass'] != $_POST['user_pass_check'])
        {
            $errors[] = 'The two passwords did not match.';
        }
    }
    else
    {
        $errors[] = 'The password field cannot be empty.';
    }
     
    if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
    {
        
	echo '<h1 class="cat_title" style="font-family: '.'Overwatch'.'; color:orange; font-size: 5em; text-align: center;">Some fields were entered incorrectly. Try again.</h1>';
        echo '<ul>';
        foreach($errors as $key => $value) /* walk through the array so all the errors get displayed */
        {
            echo '<li style="font-family:'.'Overwatch'.'; color: #D6D7E6; text-align: center; font-size: 3em;">' . $value . '</li>'; /* this generates a nice error list */
        }
        echo '</ul>';
    }
    else
    {
        //the form has been posted without, so save it
        //notice the use of mysql_real_escape_string, keep everything safe!
        //also notice the sha1 function which hashes the password
        $sql = "INSERT INTO
                    users(user_name, user_pass, user_email ,user_date, user_level)
                VALUES('" . mysqli_real_escape_string($connection, $_POST['user_name']) . "',
                       '" . sha1($_POST['user_pass']) . "',
                       '" . mysqli_real_escape_string($connection, $_POST['user_email']) . "',
                        NOW(),
                        0)";
                         
        $result = mysqli_query($connection, $sql);
        if(!$result)
        {
            //something went wrong, display the error
            echo 'Something went wrong while registering. Please try again later.';
            //echo mysql_error(); //debugging purposes, uncomment when needed
        }
        else
        {
			
			echo '<h1 class="cat_title" style="font-family: '.'Overwatch'.'; color:orange; font-size: 5em; text-align: center;">Successfully Registered. You can now <a style="color: #D6D7E6;" href="signin.php">sign in</a> and start posting!</h1>';

        }
    }
}
 
include 'footer.php';
?>