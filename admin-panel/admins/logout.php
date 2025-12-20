<?php


    session_start();
    session_unset();
    session_destroy();
    header("location: http://localhost/sistem-booking-hotel/admin-panel/admins/login-admins.php");