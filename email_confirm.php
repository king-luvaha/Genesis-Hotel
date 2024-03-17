<?php

    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');

    if(isset($_GET['email_confirmation']))
    {
        $data = filteration($_GET);

        // Check parameter existence
        if (!isset($data['email'])) {
            echo "Invalid link: Missing parameters";
            exit;
        }

        // Check email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo "Invalid link: Invalid email format";
            exit;
        }


        $query = select("SELECT * FROM `user_cred` WHERE `email`=? LIMIT 1",[$data['email']],'s');

        if(mysqli_num_rows($query)==1)
        {
            $fetch = mysqli_fetch_assoc($query);

            if($fetch['is_verified']==1){
                echo"<script>alert('Email already verified!')</script>";
            }
            else{
                $update = update("UPDATE `user_cred` SET `is_verified`=? WHERE `id`=?",[1,$fetch['id']],'ii');
                if($update){
                    echo"<script>alert('Email verification successful!')</script>";
                }
                else{
                    echo"<script>alert('Email verification failed! Server Down!')</script>";
                }
            }
            redirect('index.php');
        }
        else{
            echo"<script>alert('Invalid Link!')</script>";
            redirect('index.php');
        }
    }

?>