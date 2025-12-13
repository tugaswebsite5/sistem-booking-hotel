<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>
<?php

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    
    // get room
    $room = $conn->query("SELECT * FROM rooms WHERE status = 1 AND id='$id'");
    $room->execute();
    $singleRoom = $room->fetch(PDO::FETCH_OBJ);

    // get utilities
    $utilities = $conn->query("SELECT * FROM utilities WHERE room_id='$id'");
    $utilities->execute();
    $allUtilities = $utilities->fetchAll(PDO::FETCH_OBJ);

    if (isset($_POST['submit'])) {

        if (
            empty($_POST['email']) OR 
            empty($_POST['phone_number']) OR 
            empty($_POST['full_name']) OR 
            empty($_POST['check_in']) OR 
            empty($_POST['check_out'])
        ) {
            echo "<script>alert('one or more input fields are empty')</script>";
        } else {

            $check_in = date_create($_POST['check_in']);
            $check_out = date_create($_POST['check_out']);
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $full_name = $_POST['full_name'];
            $hotel_name = $singleRoom->hotel_name;
            $room_name = $singleRoom->name;
            $user_id = $_SESSION['id'];
            $status = "Pending";
            $payment = $singleRoom->price;
			      $days = $check_in->diff($check_out);
            
            // save price in session
            $_SESSION['price'] = $singleRoom->price * $num_days = $days->days;

            if (date("m/d/Y") > $check_in OR date("m/d/Y") > $check_out) {
                echo "<script>alert('Pick a date that is not in the past')</script>";
            } else {

                if ($check_in > $check_out OR $check_in == date("m/d/Y")) {
                    echo "<script>alert('Invalid check-in/check-out date')</script>";
                } else {

                    $booking = $conn->prepare("
                        INSERT INTO bookings 
                        (check_in, check_out, email, phone_number, full_name, hotel_name, room_name, status, payment, user_id) 
                        VALUES 
                        (:check_in, :check_out, :email, :phone_number, :full_name, :hotel_name, :room_name, :status, :payment, :user_id)
                    ");

                    $booking->execute([
                        ":check_in" => $_POST['check_in'],
                        ":check_out" => $_POST['check_out'],
                        ":email" => $email,
                        ":phone_number" => $phone_number,
                        ":full_name" => $full_name,
                        ":hotel_name" => $hotel_name,
                        ":room_name" => $room_name,
                        ":status" => $status,
                        ":payment" => $_SESSION['price'],
                        ":user_id" => $user_id,
                    ]); 

                    echo "<script>window.location.href='" . APPURL . "/rooms/pay.php'</script>";
                }
            }
        }
    }
} else {
    echo "<script>window.location.href='".APPURL."/404.php' </script>";
}

?>

<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APPURL; ?>/images/<?php echo $singleRoom->image; ?>');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
      <div class="col-md-7 ftco-animate">
        <h2 class="subheading">Welcome to MySTAY</h2>
        <h1 class="mb-4"><?php echo $singleRoom->name; ?></h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
  <div class="container">
    <div class="row justify-content-end">
      <div class="col-lg-4">
        <form action="room-single.php?id=<?php echo $id; ?>" method="POST" class="appointment-form" style="margin-top: -568px;">
          <h3 class="mb-3">Book this room</h3>
          <div class="row">

            <div class="col-md-12">
              <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="Email">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <input type="text" name="full_name" class="form-control" placeholder="Full Name">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group input-wrap">
                <div class="icon"><span class="ion-md-calendar"></span></div>
                <input type="text" name="check_in" class="form-control appointment_date-check-in" placeholder="Check-In">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group input-wrap">
                <div class="icon"><span class="ion-md-calendar"></span></div>
                <input type="text" name="check_out" class="form-control appointment_date-check-out" placeholder="Check-Out">
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <input type="submit" name="submit" value="Book and Pay Now" class="btn btn-primary py-3 px-4">
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section bg-light">
  <div class="container">
    <div class="row no-gutters">

      <div class="col-md-6 wrap-about">
        <div class="img img-2 mb-4" style="background-image: url(<?php echo APPURL; ?>/images/image_2.jpg);"></div>
        <h2>The most recommended vacation rental</h2>
        <p>A small river named Duden flows ...</p>
      </div>

      <div class="col-md-6 wrap-about ftco-animate">
        <div class="heading-section">
          <div class="pl-md-5">
            <h2 class="mb-2">What we offer</h2>
          </div>
        </div>
        <div class="pl-md-5">
          <p>A small river named Duden flows ...</p>
          <div class="row">
            <?php foreach($allUtilities as $uitility) : ?>
            <div class="services-2 col-lg-6 d-flex w-100">
              <div class="icon d-flex justify-content-center align-items-center">
                <span class="<?php echo $uitility->icon; ?>"></span>
              </div>
              <div class="media-body pl-3">
                <h3 class="heading"><?php echo $uitility->name; ?></h3>
                <p><?php echo $uitility->description; ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="ftco-intro" style="background-image: url(<?php echo APPURL; ?>/images/image_2.jpg);" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-9 text-center">
        <h2>Ready to get started</h2>
        <p class="mb-4">It’s safe to book online with us!</p>
        <p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Learn More</a> <a href="#" class="btn btn-white px-4 py-3">Contact us</a></p>
      </div>
    </div>
  </div>
</section>

<?php require "../includes/footer.php"; ?>
