<?php
    session_start();
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Master Robot</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="BackGround.css"> 
    </head>
        
    <script>
        $(function () {
            $("#header").load("Header.html");
            $("#footer").load("Footer.html");
        })
    </script>
    <body>
    <div class="container">
        <div id="header"></div>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-1"></div>
            <div class="col-sm-4">
                <form method="POST">
                    <label>Enter the Customer ID:</label></br>
                    <input type="text" id="customerid" name ="customerid"></br>
                    <label>Enter the Date:</label></br>
                    <input type="date" id="dateafter" name= "dateafter"></br>
                    <input type="submit" class="button" name="showorder" id="showorder" value="Show Orders" onclick="">
                </form>
            </div>
        </div>
        <div id="footer"></div>
    </div>
</body>
</html>
<?php
    if(isset($_POST['showorder'])) {
        echo 'begin';
        $_SESSION['custID'] = $_POST['customerid'];
        $_SESSION['dateAfterShow'] = $_POST['dateafter'];
        echo 'session'.$_SESSION['dateAfterShow'];
        echo '<script> window.location.href="ShowOrderAfterDate.php";</script>';
    }
?>