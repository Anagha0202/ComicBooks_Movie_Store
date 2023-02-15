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
                <div class="container-fluid">
                <label>ORDER DETAILS</label></br>
                <?php
                    if($_SESSION['custOrderID']) {

                        $conn = oci_connect('anagha', 'anagha', 'DESKTOP-36O879C:1521/XEPDB1')
                            or die(oci_error());
                    
                        if(!$conn) {
                            echo "Sorry, there is some issue";
                        } 
                        else { 

                            $sql = "BEGIN get_cust_info(:custOrderID, :customername, :cid, :customerPhone,
                            :customerstreet, :customercity, :customerzipcode, :grandTotal, :refcur); END;";
                            $stmt = oci_parse($conn, $sql);

                            oci_bind_by_name($stmt, ':customername', $custname, 50);
                            oci_bind_by_name ($stmt, ':cid', $cid  , 50);
                            oci_bind_by_name ($stmt, ':customerPhone', $customerPhone  , 50);
                            oci_bind_by_name ($stmt, ':customerstreet', $customerStreet  , 50);
                            oci_bind_by_name ($stmt, ':customercity', $customerCity  , 50);
                            oci_bind_by_name ($stmt, ':customerzipcode', $customerZipcode  , 50);
                            oci_bind_by_name ($stmt, ':grandTotal', $grandTotal  , 50);
                            oci_bind_by_name($stmt, ':custOrderID', $_SESSION['custOrderID'], 50);
                            $refcur = oci_new_cursor($conn);
                            oci_bind_by_name($stmt, ':REFCUR', $refcur, -1, OCI_B_CURSOR);

                            // execute the statement
                            oci_execute($stmt);

                            // treat the ref cursor as a statement resource
                            oci_execute($refcur, OCI_DEFAULT);
                            oci_fetch_all($refcur, $custrecords, null, null, OCI_FETCHSTATEMENT_BY_ROW);

                            ?> 
                            <label>Order ID: <?php echo $_SESSION['custOrderID'];?></label></br>
                            <label>Customer ID: </label><?php echo $cid?></label></br>
                            <label>Customer Name: </label><?php echo $custname?></label></br>
                            <label>Customer Phone: </label><?php echo $customerPhone?></label></br>
                            <label>Customer Address: </label><?php echo $customerStreet.','.$customerCity.','.$customerZipcode?></label></br></br>
                            <?php

                            if (!$custrecords) {
                                echo '<p>No Records Found</p>';
                            }
                            else {
                                echo '<table border="3">
                                        <tr>
                                            <th>Item ID</th>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Ordered Date</th>
                                            <th>ShippedDate</th>
                                            <th>Shipping Fees</th>
                                            <th>Discount percent</th>
                                            <th>Tax percent</th>';
                                // print one row for each record retrieved
                                // put the fields of each record in separate table cells
                                foreach ($custrecords as $row) {
                                echo '<tr>';
                                foreach ($row as $field) {
                                    print '<td>'.
                                        ($field ? htmlentities($field) : '&nbsp;').'</td>';
                                  }
                                }
                                echo '</table>
                                </br>';?>
                                <label>Grand Total=</label><?php echo $grandTotal?></label></br></br> 
                                <?php   
                        }
                    }
                }
                oci_free_statement($stmt);
                oci_free_statement($refcur);
                oci_close($conn);
                ?>
                </div>
            </div>
        </div>

        <div id="footer"></div>
    </div>
</body>
</html>
