<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>
<?php

if(!isset($_SESSION['username'])) {
     echo "<script>window.location.href='".APPURL."' </script>";  
}


    if(isset($_GET['id'])) {
        $id = $_GET['id'];

        if ($_SESSION['id'] != $id){

        }
        $bookings = $conn->query("SELECT * FROM bookings WHERE user_id='$id'");
        $bookings->execute();

        $allBookings = $bookings->fetchAll(PDO::FETCH_OBJ); 
    } else {
         echo "<script>window.location.href='".APPURL."' </script>";
    }
    


?>
<div class="container mt-5">
    <?php if(count($allBookings) > 0) : ?>
    <table class="table">
    <thead>
        <tr>
        <th scope="col">check in</th>
        <th scope="col">check out</th>
        <th scope="col">email</th>
        <th scope="col">phone number</th>
        <th scope="col">full name</th>
        <th scope="col">hotel name</th>
        <th scope="col">room name</th>
        <th scope="col">status</th>
        <th scope="col">payment</th>
        <th scope="col">created at</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($allBookings as $booking) : ?>
        <tr>
            <th scope="row"><?php echo $booking->check_in;?></th>
            <td><?php echo $booking->check_out;?></td>
            <td><?php echo $booking->email;?></td>
            <td><?php echo $booking->phone_number;?></td>
            <td><?php echo $booking->full_name;?></td>
            <td><?php echo $booking->hotel_name;?></td>
            <td><?php echo $booking->room_name;?></td>
            <td><?php echo $booking->status;?></td>
            <td><?php echo $booking->payment;?></td>
            <td><?php echo $booking->created_at;?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
    <?php else :?>
        <div class="alert alert-primary" role="alert">
        You have not made any bookings just yet
        </div>
    <?php endif; ?>
</div>

<?php require "../includes/footer.php"; ?>
 