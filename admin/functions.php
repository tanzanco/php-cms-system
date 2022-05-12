<?php 
// =========== DATABASE HELPER FUNCTIONS =========== //

function redirect($location){
    header("Location: " . $location);
    exit;
}
function query($query){
    global $connection;
    $result = mysqli_query($connection,$query);
    confirm($result);
    return $result;
}

function fetchRecords($result){
    return mysqli_fetch_array($result);
}
function count_records($result){
    return mysqli_num_rows($result);
}
// =========== END DATABASE HELPER FUNCTIONS =========== //

// =========== GENERAL HELPER FUNCTIONS =========== //

function get_user_name(){
    return isset($_SESSION['username']) ? $_SESSION['username'] : null ;
}

// =========== END GENERAL HELPER FUNCTIONS =========== //




// =========== AUTHENTICATION HELPER FUNCTIONS =========== //
function is_admin(){
    global $connection;
    if(isLoggedIn()){
        $result = query("SELECT user_role FROM users WHERE user_id =" . $_SESSION['user_id'] . "");        
        $row = fetchRecords($result);
        if($row['user_role'] == 'Admin'){
            return true;
        }else{
            return false;
        }
    }
    return false;
}


// =========== USER SPECIFIC HELPER FUNCTIONS =========== //
function get_all_user_posts(){
    return query("SELECT * FROM posts WHERE user_id=" . loggedInUserId() . "");
}
function get_all_posts_user_comments(){
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id =" . loggedInUserId() . "");
}
function get_all_user_category(){
    return query("SELECT * FROM categories WHERE user_id=". loggedInUserId() ."");
}
function get_all_user_published_post(){
    return query ("SELECT * FROM posts WHERE post_status = 'published' AND user_id=" . loggedInUserId() . "");
}
function get_all_user_draft_post(){
    return query ("SELECT * FROM posts WHERE post_status = 'draft' AND user_id=" . loggedInUserId() . "");
}
function get_all_user_approved_posts_comments(){
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id =" . loggedInUserId() . " AND comment_status = 'approved'");
}
function get_all_user_pending_posts_comments(){
    return query("SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE user_id =" . loggedInUserId() . " AND comment_status = 'Unapproved'");
}


// =========== USER SPECIFIC HELPER FUNCTIONS =========== //


/* TO FIND GET OR POST REQUEST WHICHEVER IS COMING */
function ifItIsMethod($method = null){
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }
    return false;
}

/* TO FIND IF USER IS LOGGED IN OR NOT */
function isLoggedIn(){
    if(isset($_SESSION['user_role'])){
        return true;
    }
    return false;
}


function loggedInUserId(){
    global $connection;
    if(isLoggedIn()){
        $result = query("SELECT * FROM users WHERE username= '". $_SESSION['username']."'");
        confirm($result);
        $user = mysqli_fetch_array($result);
        return mysqli_num_rows($result) >= 1 ? $user['user_id'] : false;
    }
    return false;

}

function userLikedThisPost($post_id){
    global $connection;
    $result = query("SELECT * FROM likes WHERE user_id = " . loggedInUserId() ." AND post_id = $post_id");
    confirm($result);
    return mysqli_num_rows($result) >= 1 ? true : false;
}

/* TO CHECK IF USER IS LOGGED IN AND REDIRECT USER TO PAGES */
function checkIfUserIsLoggedInAndRedirect($redirectLocation = null){
    if(isLoggedIn()){
        redirect($redirectLocation);
    }
}


function getPostLikes($post_id){
    $result = query("SELECT * FROM likes WHERE post_id = $post_id");
    confirm($result);
    echo mysqli_num_rows($result);
}


    
function users_online(){
    global $connection;
    $session = session_id();
        $time = time();  
        $time_out_in_seconds = 30;
        $time_out = $time - $time_out_in_seconds;

        $query = "SELECT * FROM users_online WHERE session = '$session' ";
        $send_query = mysqli_query($connection,$query);
        $count = mysqli_num_rows($send_query);
        if($count == 0){
            mysqli_query($connection,"INSERT INTO users_online(session,time) VALUES('$session','$time')");
        }else{
            mysqli_query($connection,"UPDATE users_online SET time = '$time' WHERE session = '$session' ");
        }

        $users_online_query =  mysqli_query($connection,"SELECT * FROM users_online WHERE time > '$time_out' ");
        return $count_user = mysqli_num_rows($users_online_query);
}


function confirm($result){
    global $connection;
    if(!$result){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    
}

function insert_categories(){
    global $connection;
    if(isset($_POST['submit'])){
        $cat_title = $_POST['cat_title'];

        if($cat_title == "" || empty($cat_title)){
            echo "This field should be not empty";
        }else{
            $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUE(?) ");
            mysqli_stmt_bind_param($stmt, 's', $cat_title);
            mysqli_stmt_execute($stmt);


            // $create_category_query = mysqli_query($connection,$query);

            if(!$stmt){
                die('QUERY FAILED' . mysqli_error($connection));
            }

        }
    }
}


function findAllCategories() {
    global $connection;
    $query = "SELECT * FROM categories ";
    $select_categories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_categories)){
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a href ='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a href ='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "</tr>";

    }
}


function deleteCategories(){
    global $connection;
    if(isset($_GET['delete'])){
        $the_cat_id = $_GET['delete'];

        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection,$query);
        header("Location: categories.php");

    }
}

/* COUNTING NUMBERS OF POSTS, COMMENTS, USERS AND CATEGORIES */

function recordCount($table){
    global $connection;
    $query = "SELECT * FROM " . $table;
    $select_all_post = mysqli_query($connection,$query);
    $result = mysqli_num_rows($select_all_post);
    confirm($result);
    return $result;
}

function checkStatus($table,$column,$status){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $result = mysqli_query($connection,$query);
    confirm($result);
    return mysqli_num_rows($result);
}
function checkUserRole($table,$column,$role){
    global $connection;
    $query = "SELECT * FROM $table WHERE $column = '$role' ";
    $result = mysqli_query($connection,$query);
    confirm($result);
    return mysqli_num_rows($result);
}


function username_exists($username){
    global $connection;
    $query = "SELECT username FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection,$query);
    confirm($result);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}

function email_exists($email){
    global $connection;
    $query = "SELECT user_email FROM users WHERE user_email = '$email' ";
    $result = mysqli_query($connection,$query);
    confirm($result);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}

function register_user($username,$email,$password){
    global $connection;
    // $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    // EASIAR WAY OF MAKING PASSWORD 

        $username = mysqli_real_escape_string($connection,$username);
        $email = mysqli_real_escape_string($connection,$email);
        $password = mysqli_real_escape_string($connection,$password);

        $query = "SELECT randSalt FROM users ";
        $select_rand_salt_query = mysqli_query($connection,$query);
        if(!$select_rand_salt_query){
            die("QUERY FAILED" . mysqli_error($connection));
        }
            $row = mysqli_fetch_array($select_rand_salt_query);
            $salt = $row['randSalt'];

            $password = crypt($password,$salt);

            $query = "INSERT INTO users(username,user_email,user_password,user_role) ";
            $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber') ";
            $register_user_query = mysqli_query($connection,$query);
            confirm($register_user_query);
        // $message = "Your Registration is submitted";    
    
   

}

function login_user($username,$password){
    global $connection;
    $username = trim($username);
    $password = trim($password);
    
    $username = mysqli_real_escape_string($connection,$username);
    $password = mysqli_real_escape_string($connection,$password);

    $query = "SELECT * FROM users WHERE username = '{$username}' ";
    $select_user_query = mysqli_query($connection,$query);
    if(!$select_user_query){
        die("QUERY FAILED" . mysqli_error($connection));
    }
    while($row = mysqli_fetch_array($select_user_query)){
       $db_user_id = $row['user_id'];
       $db_username = $row['username'];
       $db_user_password = $row['user_password'];
       $db_user_firstname= $row['user_firstname'];
       $db_user_lastname = $row['user_lastname'];
       $db_user_role = $row['user_role'];

    }

    $password = crypt($password,$db_user_password);

//if(password_verify($password,$db_user_password)){}
    if($username !== $db_username && $password !== $db_user_password ){

        header("Location: /cms/login.php");
    }elseif($username == $db_username && $password == $db_user_password){
        $_SESSION['user_id'] = $db_user_id;
        $_SESSION['username'] = $db_username;
        $_SESSION['firstname'] = $db_user_firstname;
        $_SESSION['lastname'] = $db_user_lastname;
        $_SESSION['user_role'] = $db_user_role;

        redirect("/cms/admin");
    }else{

        return false;
    }
}

function imagePlaceholder($image=''){
    if(!$image){
        return 'image_4.jpg';
    }else{
        return $image;
    }
}
function escape($string){
    //ESCAPE EVERY VALUE COMING IN OR OUT FROM SQL_DATABASE TO PROTECT IT AGAINST SQL_INJECTION
    mysqli_real_escape_string($connection,trim($string));
}



?>