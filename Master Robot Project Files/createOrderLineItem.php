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
                <h3>Order ID: 
                    <?php 
                        session_start(); 
                        echo $_SESSION['OrderID']; 
                        echo "orderid".$_SESSION['OrderID']." custid".$_SESSION['CustID'];
                    ?> </h3></br>
                    <form method="POST" action="">
                        <label>Type of item: </label> </br>
                        <input type="radio" id="radiobook" name="option">Comic Book
                        <input type="radio" id="radiomovie" name="option">Cartoon Movie<br>
                        <label>Item ID:</label> </br>
                        <select name="itemid" id = "itemid">
                            <?php
                                $conn = oci_connect('anagha', 'anagha', 'DESKTOP-36O879C:1521/XEPDB1')
                                or die(oci_error());
                                
                                if(!$conn) {
                                echo "Sorry, there is some issue";
                                } 
                                else {
                                    $query = 'SELECT * FROM StoreItem';
                                    $values = oci_parse($conn, $query);
                                    oci_execute($values);
                                    while (($row = oci_fetch_array($values, OCI_BOTH)) != false) {
                                        ?><option value="<?php echo $row[0];?>">
                                            <?php echo $row[0];?>
                                    </option>
                                    <?php
                                    }
                                }
                            ?>
                        </select> </br>
                        <label>Customer ID</label></br>
                        <input type ="text" name="CustID" id="CustID" value="<?php echo $_SESSION['CustID']; ?>" disabled></br>
                        <label>Date of Order</label></br>
                        <input type="date" id="dateOfOrder"></br>
                        <label>Quantity Of Item: </label></br>
                        <input type ="text" name="quantity" id="quantity"></br></br>
                        <input type="submit" class="button" name="add_items" id="add_items" onclick="<?php echo 'yes';?>" value ="Add another Item">                    
                        <input type="submit" class="button" name="finish_order" id="finish_order" onclick="" value ="Finish Order">  
                </form>               
            </div>
        </div>
        
        <div id="footer"></div>
    </div>
</body>
</html>
<?php
    if(array_key_exists('add_items', $_POST) || array_key_exists('finish_order', $_POST)) {
        AddItems();
    }
    
    function AddItems() {
        $conn = oci_connect('anagha', 'anagha', 'DESKTOP-36O879C:1521/XEPDB1')
            or die(oci_error());
    
        if(!$conn) {
            echo "Sorry, there is some issue";
        } 
            else {
                $sel_itemid = $_POST['itemid'];
                $shipdate = NULL;
                
                $stmt = "BEGIN createOrderLineItem(:orderID, :itemID, :customerID, :dateOfOrder, :quantity, :shippedDate, :itemcounter, :errormsg); END;";
                $funcCall = oci_parse ($conn, $stmt);
                oci_bind_by_name ($funcCall, ':errormsg', $errormsg , 50);
                oci_bind_by_name ($funcCall, ":orderID", $_SESSION['OrderID'], 10);
                oci_bind_by_name ($funcCall, ":itemID", $sel_itemid, 10);
                oci_bind_by_name ($funcCall, ":customerID", $_SESSION['CustID'], 10);
                oci_bind_by_name ($funcCall, ":dateOfOrder", $_POST['dateOfOrder'], 15);
                oci_bind_by_name ($funcCall, ":quantity", $_POST['quantity'], 10);
                oci_bind_by_name ($funcCall, ":shippedDate", $shipdate, 0);
                oci_bind_by_name ($funcCall, ":itemcounter", $_SESSION['itemCounter'], 10);
                error_reporting(E_ALL);
                oci_execute($funcCall);

                if($errormsg) {
                    echo '<script> alert("'.$errormsg.'");</script>';
                }
                else {
                    if (isset($_POST['finish_order'])){
                        echo 'button clicked';

                        $datetoday = date("d-m-Y", strtotime("+2 days"));
                        $stmt = "BEGIN setShippingDate(:orderID, :shipDate); END;";
                        $funcCall = oci_parse ($conn, $stmt);
                        oci_bind_by_name ($funcCall, ":orderID", $_SESSION['OrderID'], 10);
                        oci_bind_by_name ($funcCall, ":shipDate", $datetoday, 10);
                        oci_execute($funcCall);

                        $stmt1 = "BEGIN computeOrderCost(:orderID); END;";
                        $funcCall1 = oci_parse ($conn, $stmt1);
                        oci_bind_by_name ($funcCall1, ":orderID", $_SESSION['OrderID'], 10);
                        oci_execute($funcCall1);

                        $stmt2 = "BEGIN :grandTotal :=computeTotal(:orderID); END;";
                        $funcCall3 = oci_parse ($conn, $stmt2);
                        oci_bind_by_name ($funcCall3, ":grandTotal", $grandTotal, 10);
                        oci_bind_by_name ($funcCall3, ":orderID", $_SESSION['OrderID'], 10);
                        oci_execute($funcCall3);
                        echo 'order total='.$grandTotal;

                        echo '<script> alert("Order Confirmed."); window.location.href="index.html";</script>';
                    }
                    else {
                        $_SESSION['itemCounter']=$_SESSION['itemCounter']+1;
                        echo $_SESSION['itemCounter'];
                    }
                }
        }
    }
?>