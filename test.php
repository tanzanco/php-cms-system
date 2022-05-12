<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>

<?php

echo loggedInUserId();

if(userLikedThisPost(67)){
    echo "USER LIKED IT";
}else{
    echo "USER DID NOT LIKED IT";
}

?>