<?php include "includes/admin_header.php" ?>
<?php

$post_count = count_records(get_all_user_posts());
$comments_count = count_records(get_all_posts_user_comments()); 
$categories_count = count_records(get_all_user_category());



?>
    <div id="wrapper">
        <!-- toastr link css and js -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
        <?php
        



         ?>

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php" ?>
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome To Admin
                            
                           <i><?php echo strtoupper(get_user_name()); ?></i> 
                        </h1>
                       
                    </div>
                </div>
                <!-- /.row -->

                       
                <!-- /.row -->
                
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-file-text fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">

                    <?php
                    // $query = "SELECT * FROM posts ";
                    // $select_all_posts = mysqli_query($connection,$query);
                    // $post_count = mysqli_num_rows($select_all_posts);
                    // $post_count = recordCount('posts');
                    // // echo "<div class='huge'>{$post_count}</div>";
                    ?>


                    <div class='huge'><?php echo $post_count;?></div>
                        <div>Posts</div>
                    </div>
                </div>
            </div>
            <a href="posts.php">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                    <?php
                    // $query = "SELECT * FROM comments ";
                    // $select_all_comments = mysqli_query($connection,$query);
                    // $comments_count = mysqli_num_rows($select_all_comments);
                    // // echo "<div class='huge'>{$comments_count}</div>";
                    ?>
                    <div class='huge'><?php echo $comments_count;?></div>

                      <div>Comments</div>
                    </div>
                </div>
            </div>
            <a href="comments.php">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                    <?php
                    // $query = "SELECT * FROM categories ";
                    // $select_all_categories = mysqli_query($connection,$query);
                    // $categories_count = mysqli_num_rows($select_all_categories);
                    // // echo "<div class='huge'>{$categories_count}</div>";
                    ?>
                        <div class='huge'><?php echo $categories_count ;?></div>

                        <div>Categories</div>
                    </div>
                </div>
            </div>
            <a href="categories.php">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
                <!-- /.row -->
                <?php
                /* getting different counts and status of different columns through checkstatus function */
                // $post_published_count = checkStatus('posts','post_status','published'); 
                $post_published_count = count_records(get_all_user_published_post()); 

                // $post_draft_count = checkStatus('posts','post_status','draft');
                $post_draft_count = count_records(get_all_user_draft_post()); 

                // // $unapproved_comment_count = checkStatus('comments','comment_status','Unapproved');
                $unapproved_comment_count = count_records(get_all_user_pending_posts_comments());
                $approved_comment_count = count_records(get_all_user_approved_posts_comments());
                // $subscribers_count = checkUserRole('users','user_role','subscriber');
                
                ?>



                <div class="row">

                <script type="text/javascript">
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                        ['Data', 'Count'],
                        <?php
                        $element_text = [ 'All Posts', 'Active Posts', 'Draft Posts', 'Comments', 'Approved Comments', 'Pending Comments', 'Categories'];
                        $element_count = [$post_count, $post_published_count, $post_draft_count,$comments_count, $approved_comment_count,$unapproved_comment_count, $categories_count];
                        for ($i= 0; $i < 7; $i++) { 
                            echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
                        }
                        ?>

                        // ['posts', 1000]
                        
                        ]);

                        var options = {
                        chart: {
                            title: '',
                            subtitle: '',
                        }
                        };

                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                        chart.draw(data, google.charts.Bar.convertOptions(options));
                    }
                    </script>
                    <div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>

                </div>











            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    <?php include "includes/admin_footer.php" ?>

