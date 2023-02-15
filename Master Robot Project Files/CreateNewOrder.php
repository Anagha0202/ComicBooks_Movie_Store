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
                    <label>Enter Customer ID:</label></br>
                    <input type="text" name="custid" id="custid">
                    <input type="submit" class="button" name="createorder" id="createorder" value="Create Order" onclick="">
                </form>
            </div>
        </div>

        <div id="footer"></div>
    </div>
</body>
</html>
<?php
    if(isset($_POST['createorder'])) {
        echo 'button clicked';
        $conn = oci_connect('anagha', 'anagha', 'DESKTOP-36O879C:1521/XEPDB1')
            or die(oci_error());
    
        if(!$conn) {
            echo "Sorry, there is some issue";
        } 
        else { 
            $_SESSION['itemCounter'] = 1;    
            $_SESSION['CustID'] = $_POST['custid'];
            
            $stmt = "BEGIN :OID :=createCustOrder(:custid); END;";
            $funcCall = oci_parse ($conn, $stmt);
            oci_bind_by_name ($funcCall, ":OID", $_SESSION['OrderID'], 10);
            oci_bind_by_name ($funcCall, ":custID", $_SESSION['CustID'], 10);
            oci_execute($funcCall); 

            if($_SESSION['OrderID'] && $_SESSION['CustID']) {
                header('Location: createOrderLineItem.php');
            }
        }
    }
?>