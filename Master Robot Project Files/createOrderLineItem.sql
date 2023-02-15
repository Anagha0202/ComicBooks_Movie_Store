CREATE OR REPLACE PROCEDURE createOrderLineItem (
        custOrderID IN integer,
        itemsIDs IN varchar,
        customerID IN integer,
        orderedDate IN date,
        noOfOrdered IN integer,
        shippedDate IN date,
        lineno IN integer,
        errorMessage OUT varchar
    )
    IS 
        quantity integer;
        shipFee float;
        customerType varchar(10);
    BEGIN
        SELECT NoOfCopies INTO quantity 
        FROM StoreItem
        WHERE ItemID=itemsIDs;
        
        IF noOfOrdered > quantity THEN 
            errorMessage := 'Quantity of chosen item is not available';
            DBMS_OUTPUT.PUT_LINE('Quanitty large');
            RETURN;
        ELSE 
            SELECT CustType INTO customerType
            FROM Customer
            WHERE CustID = customerID;

            IF customerType='GOLD' THEN 
                shipFee := 0;
            ELSIF customerType='REGULAR' THEN 
                shipFee := 10.00;
            END IF;
            DBMS_OUTPUT.PUT_LINE('shippingfee'||shipFee);

            UPDATE CustOrder 
            SET 
            ShippedDate = NULL,
            ShippingFee = shipFee,
            DateOfOrder = SYSDATE
            WHERE OrderID=custOrderID;

            INSERT INTO OrderLineItem (OrderID, ItemID, Quantity, LineID) 
            VALUES (custOrderID, itemsIDs, noOfOrdered, lineno);

            UPDATE STOREITEM SET NOOFCOPIES=(NOOFCOPIES-noOfOrdered) 
            WHERE ITEMID=itemsIDs;

        END IF;
    END; 
    /