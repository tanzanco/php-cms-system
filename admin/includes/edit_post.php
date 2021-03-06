<?php
        if(isset($_GET['p_id'])){
            $the_post_id = $_GET['p_id'];
        }

            $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
            $select_posts_by_id = mysqli_query($connection,$query);

            while($row = mysqli_fetch_assoc($select_posts_by_id)){
                $post_id = $row['post_id'];
                $post_user = $row['post_user'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_content = $row['post_content'];
                $post_comment_count = $row['post_comment_count'];
                $post_date = $row['post_date'];
            }
        if(isset($_POST['update_post'])){
            $post_title = $_POST['title'];
            $post_user = $_POST['post_user'];
            $post_category_id = $_POST['post_category'];
            $post_status = $_POST['post_status'];
            // $post_image = $_FILES['image']['name'];
            // $post_image_temp = $_FILES['image']['tmp_name'];
            $post_tags = $_POST['post_tags'];
            $post_content = $_POST['post_content'];

            // move_uploaded_file($post_image_temp, "../images/$post_image");
            if(empty($post_image)){
                $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
                $select_image = mysqli_query($connection,$query);
                while ($row = mysqli_fetch_array($select_image)){
                    $post_image = $row['post_image'];
                }
            }

            $query = "UPDATE posts SET ";
            $query .= "post_title = '{$post_title}', ";
            $query .= "post_user = '{$post_user}', ";
            $query .= "post_category_id = '{$post_category_id}', ";
            $query .= "post_date = now(), ";
            $query .= "post_status = '{$post_status}', ";
            $query .= "post_tags = '{$post_tags}', ";
            $query .= "post_content = '{$post_content}', ";
            $query .= "post_image = '{$post_image}' ";
            $query .= "WHERE post_id = {$the_post_id} ";

            $update_post = mysqli_query($connection,$query);
            confirm($update_post);

            echo "<p class='bg-success'> Post Updated. <a href='../post.php?p_id={$the_post_id}'> View Post</a> or <a href='posts.php'>Edit More Posts</a></p>";
            
        }
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title ;?>" type="text" name="title" class="form-control">
    </div>

    <div class="form-group">
        <label for="categories">Categories</label>
        <select name="post_category" id="">
            <?php
            $query = "SELECT * FROM categories ";
            $select_categories = mysqli_query($connection, $query);
            confirm($select_categories);
            while($row = mysqli_fetch_assoc($select_categories )){
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];

            if($cat_id == $post_category_id){
                echo "<option selected value='{$cat_id}'>{$cat_title}</option>";

            }else{
                echo "<option value='{$cat_id}'>{$cat_title}</option>";

            }

            }
            
            ?>
        </select>
        
    </div>

    <!-- <div class="form-group">
        <label for="author">Post Author</label>
        <input value="<?php echo $post_author ;?>" type="text" name="author" class="form-control">
    </div> -->
    
    

    <div class="form-group">
        <label for="post author">Post Author</label>
        <select name="post_user" id="">
        <?php echo "<option value='{$username}'>{$post_user}</option>";?>

            <?php
            $query = "SELECT * FROM users ";
            $select_users = mysqli_query($connection, $query);
            confirm($select_users);
            while($row = mysqli_fetch_assoc($select_users)){
            $user_id = $row['user_id'];
            $username = $row['username'];

            echo "<option value='{$username}'>{$username}</option>";
            }
            
            ?>
        </select>
        
    </div>
    <div class="form-group">
        <select name="post_status" id="">
            <option value='<?php echo $post_status; ?>'><?php echo $post_status; ?></option>
            <?php 
            if($post_status == 'published'){
                echo "<option value='draft'>Draft</option>";
            }else{
                echo "<option value='published'>Publish</option>";
            }
            ?>
            
        </select>
    </div>

    <!-- <div class="form-group">
        <label for="post_status">Post Status</label>
        <input value="<?php// echo $post_status ;?>" type="text" name="post_status" class="form-control">
    </div> -->


    
    <div class="form-group">
        <img width="100" src="../images/<?php echo $post_image ;?>"  name ="post_image" type="file" alt="">
    </div>
    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags ;?>" type="text" name="post_tags" class="form-control">
    </div>
    <div class="form-group">
         <label for="post_content">Post Content</label> <!--if you have problem after escaping every value -->
        <textarea class="form-control" name="post_content" id="" cols="30" rows="10"><?php echo $post_content ;//str_replace('\r\n','</br>',$post_content);?>
        </textarea>
    </div>
    <div class="form-group">
        <input type="submit" name="update_post" class="btn btn-primary" value="Update Post">
    </div>
</form>
