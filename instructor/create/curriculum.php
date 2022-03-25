<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $conn = mysqli_connect("localhost", "root", "", "edusoft");
    if(!$conn){
        die("Error: " .mysqli_connect_error());
    }

    if(isset($_SESSION['loggedin'])){
        $user_id = $_SESSION['user_id'];
        echo $user_id;
    }

    $lecture_id = $_POST['l_id'];
    $lecture_title = $_POST['l_title'];
    $course_id = mysqli_real_escape_string($conn, $_POST['c_id']);
    $filename = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $valid_extensions = array("mp4", "mov", "mpeg");
    $video_lec_id = $_POST['video_lec_id'];

    if(in_array($extension, $valid_extensions)){
        $new_name = rand() . "." . $extension;
        $file_destination = "upload/".$new_name;
        
        if(move_uploaded_file($_FILES['file']['tmp_name'], $file_destination)){
            $sql = "INSERT INTO `lectures` (`lecture_id`, `user_id` , `lecture_title`, `lecture_content` ,`course_id`, `st`) VALUES ('$lecture_id', '$user_id' ,'$lecture_title', '$new_name' ,'$course_id', current_timestamp())";
            if($result = mysqli_query($conn, $sql)){
                echo true;
            }
            else{
                echo false;
            }
        }
    }
    }
?>

<!-- UPDATE `lectures` SET `lecture_content` = 'kjugj' WHERE `lectures`.`lecture_id` = 1; -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curriculum</title>
    <link rel="stylesheet" href="create.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="goals_navbar">
        <a href="/project/instructor/course.php">Back to courses</a>
        <span>web d</span>
        <div>
            <button class="save">Save</button>
        </div>
    </div>
    
    <div id="alert1">

    </div>
    <div id="container">
        <div id="sidecontentbar">
            <div class="heading">Plan your course</div>
            <div class="course_items">
                <ul>
                    <li> <a href="/project/instructor/create/intended_learners.php"> Intended learners</a></li>
                    <li> <a href="/project/instructor/create/curriculum.php"> Curriculum</a></li>
                    <li> <a href="/project/instructor/create/landingpage.php"> Course landing page</a></li>
                    <li> <a href="/project/instructor/create/pricing.php"> Pricing</a></li>
                </ul>
            </div>
        </div>


        <div class="main_page">
            <h1>Curriculum</h1>
            <div class="main_course">
                <div class="alert alert-dismissible fade show" id="alert" role="alert">
                    <div id="alert_icon">
                        <i class='bx bxs-info-circle'></i>
                    </div>
                    <div id="alert_desc">
                        <p>Here’s where you add course content—like lectures, course sections, assignments, and more. Click
                            a + icon on the left to get started.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" id="alert_close" aria-label="Close"></button>
                </div>

                <div id="lecture_box">
                    <div id="lecture">
                        <input type="hidden" value="1" id="course_id">
                        <div>
                            <p>New Lecture:</p>
                        </div>
                        <div id="lecture_form">
                            <input type="text" name="lecture_title" id="lecture_title" placeholder="Enter a title">
                            <input type="submit" id="add_lecture" name="add_lecture" onclick="addLec()" value="Add Lecture">
                        </div>

                    </div>
                </div>
            </div>

            <input type="submit" id="submit_form" value="Submit" style="margin-left: 46px;">
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="cur.js"></script>
    <script src="/project/js/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var count = 0; 
            var course_id = localStorage.getItem("c_id");

            // $(document).on("click", "#add_lecture", function(e){
            //     e.preventDefault();
            //     count++;

            //     var lecture_id = count;
                
            //     $.ajax({
            //         url : "ajax_demo.php", 
            //         type : "POST", 
            //         data : {l_title:lecture_title, l_id:lecture_id, c_id:course_id},
            //         success : function(data){
                        
            //         }
            //     })
            // }); 

            $(document).on("submit", "#submit_lec", function(e){
                e.preventDefault();

                count++;
                var lecture_id = count;
                var formData = new FormData(this);

                formData.append("c_id", course_id);
                formData.append("l_id", lecture_id);
                formData.append("l_title", lecture_title);

                $.ajax({
                    url : "curriculum.php",
                    type : "POST",
                    data : formData,
                    contentType : false,
                    processData : false,
                    success : function(data){
                        console.log(data);
                    }
                })
            })
        });
    </script> 
</body>

</html>