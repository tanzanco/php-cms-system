<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<!-- header include php -->


    <!-- Navigation -->
    <!-- navigation include php -->
    <?php include "includes/navigation.php" ?>

<?php
    if(isset($_POST['liked'])){
        echo 'LIKED';
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        // 1. FETCHING THE RIGHT POSTS
        $query = "SELECT * FROM posts WHERE post_id = $post_id";
        $postResult = mysqli_query($connection,$query);
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];
        
        // 2. UPDATE POST WITH LIKES
        $query = "UPDATE posts SET likes = $likes +1 WHERE post_id = $post_id";
        $result = mysqli_query($connection,$query);
        // 3. CREATE LIKES FOR POSTS
        $query = "INSERT INTO likes(user_id,post_id) VALUES($user_id,$post_id)";
        $result = mysqli_query($connection,$query);


        // LIKE COUNT

        $query = "SELECT likes FROM posts WHERE post_id = {$post_id}";
        $result = mysqli_query($connection,$query);
        $key = mysqli_fetch_array($result);
        $likes = $key['likes'];

    }

     if(isset($_POST['unliked'])){

        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        // 1. FETCHING THE RIGHT POSTS
        $query = "SELECT * FROM posts WHERE post_id = $post_id";
        $postResult = mysqli_query($connection,$query);
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];

        // 2. DELETE LIKES
        $query = "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id";
        $result = mysqli_query($connection,$query);


        // 3. UPDATE DECREMENT LIKES
        $query = "UPDATE posts SET likes = $likes -1 WHERE post_id = $post_id";
        $result = mysqli_query($connection,$query);

        exit();
    }

?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            <?php
            if(isset($_GET['p_id'])){
                $the_post_id = $_GET['p_id'];
            

            $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id ";
            $send_query = mysqli_query($connection,$view_query);

            if(!$send_query){
                die("QUERY FAILED" . mysqli_error($connection));
            }
            if(isset($_SESSION['user_role']) && $_SESSION == 'admin'){
                $query = "SELECT * FROM posts WHERE post_id = {$the_post_id} ";
            }else{
                $query = "SELECT * FROM posts WHERE post_id = {$the_post_id} AND post_status = 'published' ";
            }
                $query = "SELECT * FROM posts WHERE post_id = {$the_post_id} ";
                $select_all_posts_query = mysqli_query($connection, $query);
                    while($row = mysqli_fetch_assoc($select_all_posts_query)){
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_user = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

                        ?>

                <h1 class="page-header">
                    Posts
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="#"><?php echo $post_title; ?></a>
                </h2>
                <p class="lead">
                    by <a href="/cms/index.php"><?php echo $post_user; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
                <hr>
                <img class="img-responsive" src="/cms/images/<?php echo $post_image;?>" alt="">
                <hr>
                <p><?php echo $post_content; ?></p>
                <hr>

                <?php if(isLoggedIn()){  ?>

                    <div class="row">
                    <p class="pull-right"><a
                                class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like'; ?>"
                                href=""><span class="glyphicon glyphicon-thumbs-up"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="<?php echo userLikedThisPost($the_post_id) ? ' I liked this before' : 'Want to like it?'; ?>"



                                ></span>
                                <?php echo userLikedThisPost($the_post_id) ? ' Unlike' : ' Like'; ?>




                            </a></p>
                    </div>
               <?php } else{ ?>
                    <div class="row">
                        <p class="pull-right login-post">You need to <a href="/cms/login.php"> Login </a> to like </p>
                    </div>

              <?php } ?>
               
                
               
                
                <div class="row">
                    <p class="pull-right likes">Likes : <?php getPostLikes($the_post_id); ?></p>
                </div>
                <div class="clearfix"></div>

                  <?php  } 
                   ?>


                  <!-- Blog Comments -->

                  <?php
                if(isset($_POST['create_comment'])){
                    $the_post_id = $_GET['p_id'];
                    $comment_author = $_POST['comment_author'];
                    $comment_email = $_POST['comment_email'];
                    $comment_content = $_POST['comment_content'];

                    if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)){
                        $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                        $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now()) ";

                        $create_comment_query = mysqli_query($connection,$query);
                        if(!$create_comment_query){
                            die("QUERY FAILED . mysqli_error($connection)");
                        }
                        // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1  ";
                        // $query .= "WHERE post_id = $the_post_id ";
                        // $update_comment_count = mysqli_query($connection,$query);
                    } 

                    
                }
                  ?>

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form role="form" action="" method="post" >
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input type="text" name="comment_author" class="form-control" name="comment_author" id="">
                        </div>

                        <div class="form-group">
                            <label for="author">Email</label>
                            <input type="email" name="comment_email" class="form-control" name="comment_email" id="">
                        </div>

                        <div class="form-group">
                            <label for="comment">Your Comment</label>
                            <textarea name="comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <?php
            $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
            $query .= "AND comment_status = 'Approved' ";
            $query .= "ORDER BY comment_id DESC ";
            $select_comment_query = mysqli_query($connection,$query);
            if(!$select_comment_query){
                die("QUERY FAILED" . mysqli_error($connection));
            }
            while($row = mysqli_fetch_assoc($select_comment_query)){
                $comment_date = $row['comment_date'];
                $comment_content = $row['comment_content'];
                $comment_author = $row['comment_author'];
                ?>
                 <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author ;?>
                            <small><?php echo $comment_date ;?></small>
                        </h4>
                        <?php echo $comment_content ;?>
                    </div>
                </div>


             <?php } }else{
                      header("Location: index.php");
                  }

            ?>

                <!-- Comment -->
               

                <!-- Comment -->
                
                
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <!-- sidebar include php -->
            <?php include "includes/sidebar.php" ?>


        </div>
        <!-- /.row -->

        <hr>
<!-- footer include php -->
<?php include "includes/footer.php" ?>
<script>
    // LIKING
    $(document).ready(function(){
        $("[data-toggle='tooltip']").tooltip();
        var post_id = <?php echo $the_post_id; ?>;
        var user_id = <?php echo loggedInUserId(); ?>;
        $('.like').click(function(){
            $.ajax({
                url:"/cms/post.php?p_id=<?php echo $the_post_id; ?>",
                type: 'post',
                data: {
                    'liked': 1,
                    'post_id': post_id,
                    'user_id': user_id,

                }
            });
        });
        // UNLIKING
        $('.unlike').click(function(){
            $.ajax({
                url:"/cms/post.php?p_id=<?php echo $the_post_id; ?>",
                type: 'post',
                data: {
                    'unliked': 1,
                    'post_id': post_id,
                    'user_id': user_id,

                }
            });
        });
    });
    
</script>