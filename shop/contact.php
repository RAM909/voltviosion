<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <form id="contact-form" action="https://api.web3forms.com/submit" method="post">
      <h3>Get in touch.</h3>
      <input type="hidden" name="access_key" value="68ee17e0-29c5-43d3-a4c4-57d4715e44a4">
      <input type="text" name="first_name" placeholder="enter your first name" required maxlength="20" class="box">
      <input type="text" name="last_name" placeholder="enter your last name" required maxlength="20" class="box">
      <input type="email" name="email_address" placeholder="enter your email" required maxlength="50" class="box">
      <input type="text" name="subject" placeholder="enter the subject" maxlength="50" class="box">
      <textarea name="message" class="box" placeholder="enter your message" cols="30" rows="10" required></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
   document.getElementById('contact-form').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission

      var form = event.target;

      fetch(form.action, {
         method: form.method,
         body: new FormData(form),
      }).then(function(response) {
         if (response.ok) {
            form.reset(); // Clear the form fields
            alert('Message sent successfully!');
         } else {
            alert('Failed to send message. Please try again.');
         }
      }).catch(function(error) {
         alert('Failed to send message. Please try again.');
      });
   });
</script>

</body>
</html>