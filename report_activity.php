<?php
    require 'header.php';
    if( !isset($_SESSION['username'])){
        echo "You are not authorized to view this page. Go back <a href= '/'>home</a>";
        exit();
    }
    else if(!isset($_GET['id'])){
        header("Location: index.php");
        exit();   
    }

    require 'db_key.php';
    $conn = connect_db();
    
    $activity = null;
    $sql = "select activity_id, activity_name, username from activity_table where activity_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: admin_index.php?error=sqlerror");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $activity = mysqli_fetch_assoc($result);
    }
?>

<div class="container mx-3">
    <h1>Please fill in the form</h1>
    
    <form action="report_backend.php" method="POST">
        <!-- this field is hidden -->
        <div class="form-group">
            <input type="hidden" name="activity_id" value="<?php echo $activity['activity_id'] ?>">
        </div>
        
        <div class="form-group">
            <label for="activity_name">Activity name:</label>
            <input class="form-control w-50" type="text" name="activity_name" value="<?php echo $activity['activity_name'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="activity_creator">Activity creator:</label>
            <input class="form-control w-50" type="text" name="activity_creator" value="<?php echo $activity['username'] ?>" readonly>
        </div>
        <div class="form-group">
            <label for="reason">Reason:</label>
            <textarea class="form-control" name="reason" required></textarea>
        </div>

        <button type="submit" class="btn btn-success" name="report_activity">Submit</button>
    </form>
</div>

<?php
    require "footer.php";
?>
