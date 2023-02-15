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
                <form method="POST" action="">
                    <label>Enter the Customer ID:</label></br>
                    <input type="text" name="custID" id="custID">
                    <input type="submit" class="button" name="ty" id="ty" onclick="" value ="Change Customer Type"> 
                </form>
            </div>
            <div id="footer"></div>
    </body>
</html>
<?php
    if (isset($_POST['ty'])){
        echo 'button clicked';
        echo 'connected';
        
        $conn = oci_connect('anagha', 'anagha', 'DESKTOP-36O879C:1521/XEPDB1')
            or die(oci_error());
    
        if(!$conn) {
            echo "Sorry, there is some issue";
        } 
        else { 
            $stmt = "BEGIN updateCustType(:customerID); END;";
            $funcCall = oci_parse($conn, $stmt);
            oci_bind_by_name ($funcCall, ":customerID", $_POST['custID'], 10);
            error_reporting(E_ALL);
            oci_execute($funcCall);

            $stmt2 = "BEGIN computeTotalOnCustType(:customerID); END;";
            $funcCall3 = oci_parse ($conn, $stmt2);
            oci_bind_by_name ($funcCall3, ":customerID", $_POST['custID'], 10);
            oci_execute($funcCall3);

            if ($funcCall && $funcCall3) {
                echo '<script> alert("Customer Type is changed to Gold");</script>';
            }
            else {
                echo oci_error();
            }            
        }    
    }
?>