<?php

//error_reporting(0); //= Comment out error_reporting(0) while in production
session_start();
$link = false;

//= This function is to set up the connection from the appication to the database
//= Set up in prepared.php
function SETUP($host, $username, $password, $dbname) {
    global $link;

    define("HOST", "$host");
    define("USERNAME", "$username");
    define("PASSWORD", "$password");
    define("DBNAME", "$dbname");

    $link = mysqli_connect(HOST, USERNAME, PASSWORD, DBNAME);
}

//= This function checks of the connection was successful
function CHECK_CONNECTION() {
    global $link;

    if ($link) {
        echo "Your connection to the database was successful";
        echo "<script>alert('Your connection to the database was successful')</script>";
        echo "<script>console.log('Your connection to the database was successful')</script>";
    } else {
        echo ERROR("Your connection failed to connect!");
        echo "<script>alert('Your connection failed to connect!')</script>";
        echo "<script>console.error('Your connection failed to connect!')</script>";
    }
}

//= This function is for sanitizing your inputs, so as to avoid SQL-INJECTIONS
function SANITIZE($input) {
    return htmlentities(htmlspecialchars(strip_tags(trim($input))));
}

//= This function is for allowing safe symbols
function ALLOW_SAFE_SYMBOLS($input) {
    return str_replace("'", "&#39;", "$input");
}

//=     
function CHECK_INPUT($input) {
    if (empty($input)) {
        echo ERROR("Please fill in all the fields!");
        die();
    } else {
        return $input;
    }
}

//= This function is for easy redirections.
function REDIRECT($url = null) {
    if ($url != null) {
        header("Location: " . $url);
    } else {
        echo ERROR("Provide accurate argument for REDIRECT");
    }

}

//= This function is for granting access to users.
function ACCESS($user = null, $url = null) {
    if (!isset($user)) {
        REDIRECT($url);
    }
}

//= This function is for fetching data from the database.
function FETCH_ASSOC_QUERY($query) {
    global $link;

    $fQuery = $query;
    $fExecute = mysqli_query($link, $fQuery);

    if (mysqli_num_rows($fExecute) > 0) {
        $rows = mysqli_fetch_assoc($fExecute);
        return $rows;
    } else {
        return false;
    }
}

//= This function is for executing a SQL statement
function EXECUTE_QUERY($query) {
    global $link;

    $fQuery = $query;
    $fExecute = mysqli_query($link, $fQuery);

    if (mysqli_num_rows($fExecute) > 0) {
        return $fExecute;
    } else {
        return false;
    }
}

//= This function for validating SQL statements
function VALIDATE_QUERY($query) {
    global $link;

    $fExecute = mysqli_query($link, $query);
    if ($fExecute) {
        return true;
    } else {
        return ERROR("Please check your query well");
    }
}

//= This function is for SELECTING ALL data from the database
function SELECT_ALL($table = null, $id) {
    if ($table != null) {
        return "SELECT * FROM $table ORDER BY $id DESC";
    }
    return "false";
}

//= This function is for SELECTING ALL data in ASCENDING ORDER from the database
function SELECT_ALL_ASC($table = null, $id) {
    if ($table != null) {
        return "SELECT * FROM $table ORDER BY $id ASC";
    }
    return "false";
}

//= This function is for SELECTING ALL data in your desired order from the database
function SELECT_ALL_ORDER($table = null, $id = null, $order = null) {
    if ($table != null && $id != null && $order != null) {
        return "SELECT * FROM $table ORDER BY $id $order";
    }
    echo "<scrip>alert('Provide accurate arguments for SELECT_ALL_ORDER')</script>";
}

//= This function is for SELECTING data in your desired limit
function SELECT_ALL_LIMIT($table = null, $id = null, $start = null, $end = null) {

    if (isset($table) && isset($id) && isset($start) && isset($end)) {
        return "SELECT * FROM $table ORDER BY $id DESC LIMIT $start, $end";
    } else {
        return ERROR("Provide accurate arguments for SELECT_ALL_LIMIT");
    }
}

//= This function is for SELECTING a specified data from the database
function SELECT_WHERE($table = null, $col = null, $data = null) {

    if ($table != null && $col != null && $data != null) {
        return "SELECT * FROM $table WHERE $col = '$data'";
    }
    echo "<script>alert('Provide accurate arguments for SELECT_WHERE')</script>";
}

//= This function is for SELECTING a specified limited data from the database
function SELECT_LIMIT_WHERE($table = null, $col = null, $data = null, $id = null, $start = null, $end = null) {

    if ($table != null && $col != null && $data != null && $id != null && $order != null) {
        return "SELECT * FROM $table WHERE $col = '$data' ORDER BY $id DESC LIMIT $start, $end";
    }
    echo "<script>alert('Provide accurate arguments for SELECT_LIMIT_WHERE')</script>";

    $query = "SELECT * FROM $table WHERE $col = '$data' ORDER BY id DESC LIMIT $limit";
    $execute = mysqli_query($link, $query);

    if (mysqli_num_rows($execute) > 0) {
        return $execute;
    } else {
        return false;
    }
}

//= This function is for getting total number of rows from the database
function GET_TOTAL($table = null) {
    global $link;

    if ($table != null) {
        $query = "SELECT * FROM $table";
        $execute = mysqli_query($link, $query);

        if($execute) {
            if ($execute) {
                return mysqli_num_rows($execute);
            } else {
                return false;
            }
        } else {
            return ERROR("Table called " . strtoupper($table) . " was not found for GET_TOTAL FUNCTION!");
        }
    } else {
        return ERROR("Provide accurate argument for GET_TOTAL");
    }

}

//= This function is for getting total number in a specified column from the database
function GET_TOTAL_WHERE($table = null, $col = null, $data = null) {
    global $link;

    if ($table != null && $col != "null" && $data != "null") {
        $query = "SELECT * FROM $table WHERE $col = '$data'";
        $execute = mysqli_query($link, $query);

        if ($execute) {
            if (mysqli_num_rows($execute) > 0) {
                return mysqli_num_rows($execute);
            } else {
                return false;
            }
        } else {
            return ERROR("Check if <i>" . strtoupper($table) . "</i> table or <i>" . strtoupper($col) . "</i> column matches with what you have in the database" );
        }
    }
}

//= This function is for checking duplicate data in a specified column from the database
function CHECK_DUPLICATE($table, $data, $incoming_data) {
    global $link;

    $query = "SELECT * FROM $table WHERE $data = '$incoming_data'";
    $execute = mysqli_query($link, $query);

    if (mysqli_num_rows($execute) > 0) {
        return true;
    } else {
        return false;
    }
}

//= This function is for encrypting data being sent to the database
function ENCRYPT($data) {
    $data = sha1(md5($data));
    return "514318251620" . $data;
}

//= This function is for decrypting data being received from the database
function DECRYPT($old_data, $new_data) {
    $new_data = sha1(md5($new_data));
    $new_data = "514318251620" . $new_data;

    if ($new_data != $old_data) {
        return false;
    } else {
        return true;
    }
}

//= This function is for sending emails to users
function SEND_MAIL($to, $from, $email, $subject, $msg) {
    $to = $to;
    $subject = $subject;

    $message = "
    <html>
    <body>
    <h3>Hello $email,</h3>
    <p>Hi There!</p>
    <p>$msg</p>

    <br><br>
    <hr>
    <p><i>From $site</i></p>
    </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= "From: <$from>" . "\r\n";

    mail($to,$subject,$message,$headers);
}

//= This function is for sending login emails to users
function SEND_LOGIN_MAIL($to, $from, $username, $email, $subject, $logout_link) {
    $to = $to;
    $subject = $subject;

    $message = "
    <html>
    <body>
    <h3>Hello $username,</h3>
    <p>You just logged in to your account as $email</p>
    <p>If you did'nt initiate this login, please click on the link below!</p>
    <a href='$logout_link' style='padding: 15px; background: #ddd; border-radius: 5px; text-decoration: none; color: #000;'>Click to log user out</a>
    </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= "From: <$from>" . "\r\n";

    mail($to,$subject,$message,$headers);
}

//= This function is for sending registration emails to users
function SEND_REGISTER_MAIL($to, $from, $username, $email, $subject, $site) {
    $to = $to;
    $subject = $subject;

    $message = "
    <html>
    <body>
    <h3>Hello $username,</h3>
    <p>Hi There!</p>
    <p>We just wanted to let you know your user account on $site has been created.  Just as a reminder, please find your username that you signed up with below.</p>
    <hr>
    <p>Username: $username</p>
    <p>Email: $email</p>
    <p>Password: <i>Your password</i></p>
    <br><br>
    <hr>
    <p><i>From $site</i></p>
    </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= "From: <$from>" . "\r\n";

    mail($to,$subject,$message,$headers);
}

//= This function is for users email verification
function SEND_REGISTER_MAIL_CONFIRMATION($to, $from, $username, $email, $subject, $confirm_link) {
    $to = $to;
    $subject = $subject;

    $message = "
    <html>
    <body>
    <h3>Hello $username,</h3>
    <p>Hi There!</p>
    <p>We just wanted to let you know your user account on $site has been created.  Just as a reminder, please click on the link below to confirm your email.</p>
    <hr>
    <a href='$confirm_link' style='padding: 15px; background: lightblue; border-radius: 5px; text-decoration: none; color: #000;'>Click to log user out</a>
    <br><br>
    <hr>
    <p><i>From $site</i></p>
    </body>
    </html>
    ";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= "From: <$from>" . "\r\n";

    mail($to,$subject,$message,$headers);
}

//= This function is for beautifying application url
function FORMAT_URL($url = null) {
    if ($url != null) {
        $tmp_url = str_replace(" ", "-", $url);
        $url = str_replace("&#39;", ":", $tmp_url);

        return $url;
    } else {
        echo "<script>alert('Please provide accurate URL LINK for formatting')</script>";
        return ERROR("Please provide accurate URL LINK for formatting");
    }
}

//= This function is for returning application url to normal
function RETURN_URL($url = null) {
    if ($url != null) {
        $tmp_url = str_replace("-", " ", $url);
        $url = str_replace(":", "&#39;", $tmp_url);

        return $url;
    } else {
        echo "<script>alert('Please provide accurate URL LINK for formatting')</script>";
        return ERROR("Please provide accurate URL LINK for formatting");
    }
}

//= This function for formatting date & time to a readable format
function HUMAN_DATE($timestamp) {
    return date("d F, Y", strtotime($timestamp));
}

function HUMAN_TIME($timestamp, $second = null) {
    if ($second != null) {
        return date("h:i:" . $second . "A", strtotime($timestamp));
    } else {
        return date("h:iA", strtotime($timestamp));
    }
    
}

//= This function is for reducing long text strings
function SUB_STRING($str, $length = 100) {
    return substr($str, 0, $length) . "...";
}

//= This function is for setting sessions
function SET_SESSION($session_name, $value) {
    $_SESSION[$session_name] = $value;
}

//= This function is for setting cookies
function SET_COOKIE($cookie_name, $cookie_value, $cookie_duration = 30) {
    setcookie($cookie_name, $cookie_value, time() + (86400 * $cookie_duration), "/");
}

//= This function is for getting cookies
function GET_COOKIE($cookie_name) {
    if (!isset($_COOKIE[$cookie_name])) {
        return ERROR("No such cookie as " . strtoupper($cookie_name));
    } else {
        return $_COOKIE[$cookie_name];
    }
}

//= This function is for getting sessions
function GET_SESSION($session_name) {
    if (!isset($_SESSION[$session_name])) {
        return ERROR("No such session as " . strtoupper($session_name));
    } else {
        return $_SESSION[$session_name];
    }
}

//= This function is for logging out users from the application 
function LOGOUT($url) {
    session_destroy();
    REDIRECT($url);
}

//= This function is for displaying errors to the screen in production stage
function ERROR($msg) {
    echo "<script>console.clear()</script>";
    echo "<script>console.error('MYSQLI LIB ERROR => ', '$msg')</script>";
    return "<div style='background: #ff9000; padding: 10px; text-align: center; box-shadow: 2px 1px 6px #000; border: 2px dashed #fff; z-index: 3000; font-size: 23px; margin-top: 102px; color: #000;'>
    $msg
</div>";
}

