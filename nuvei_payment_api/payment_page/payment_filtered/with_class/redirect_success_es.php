<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js">

    </script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

    <title>Message</title>
</head>

<body>
    <?php

    echo "<script>Swal.fire({
    icon: 'success',
    title: 'EL PAGO SE HA PROCESADO CON ÉXITO.',
    showConfirmButton: false,
    timer: 1500
  });setInterval(function(){
    window.location.replace(\"https://mexplay.mx/profile\")
},2000)</script>";
    ?>

</body>

</html>