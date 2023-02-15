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
                <?php
                    if($_SESSION['custID']) {

                        $conn = oci_connect('anagha', 'anagha', 'DESKTOP-36O879C:1521/XEPDB1')
                            or die(oci_error());
                    
                        if(!$conn) {
                            echo "Sorry, there is some issue";
                        } 
                        else { 

                            echo 'ORDER DETAILS OF CUSTOMER ID: '.$_SESSION['custID'];
                            $curafter = oci_new_cursor($conn);
                            $sql = "BEGIN showItemOrdersAfter(:custID, :date, :refcur); END;";
                            $stmt = oci_parse($conn, $sql);
                            
                            oci_bind_by_name($stmt, ':refcur', $curafter, -1, OCI_B_CURSOR);
                            oci_bind_by_name($stmt, ':custID', $_SESSION['custID'], 50);
                            oci_bind_by_name($stmt, ':date', $_SESSION['dateAfterShow'], 50);

                            // execute the statement
                            oci_execute($stmt);

                            // treat the ref cursor as a statement resource
                            oci_execute($curafter, OCI_DEFAULT);
                            echo '<table border="1">';
                            echo '<tr>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Customer Phone</th>
                                    <th>Street</th>
                                    <th>City</th>
                                    <th>ZipCode</th>
                                    <th>Order ID</th>
                                    <th>Item ID</th>
                                    <th>Item Title</th>
                                    <th>Item Quantity</th>
                                    <th>Order Date</th>
                                    <th>Shipping Date</th>
                                    <th>Order Cost For Order</th>
                                    <th>Shipping Fee</th>
                                    <th>Discount Percent</th>
                                    <th>Tax Percent</th>
                                    <th>Grand Total For Order</th>
                                </tr>';
                            
                            while (($row = oci_fetch_assoc($curafter))) {
                                echo '<tr>';
                                foreach ($row as $col) {
                                    echo '<td>'.$col.'</td>';
                                }
                                echo '</tr>';
                            }
                            }
                        }
                        oci_free_statement($stmt);
                        oci_free_statement($curafter);
                        oci_close($conn);
                    
                ?>
            </div>
        </div>
        
    </div>
</body>
</html>