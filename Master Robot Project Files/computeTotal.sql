CREATE OR REPLACE FUNCTION computeTotal(custOrderID IN integer)
RETURN float
AS
customerType varchar(10);
customerID CustOrder.CUSTID%TYPE; 
shipfee CustOrder.SHIPPINGFEE%TYPE; 
itemscost CustOrder.ORDERCOST%TYPE;
discountedCost float;
grandtotal float;
taxPercent float DEFAULT 5;
discountPercent float DEFAULT 0.0;

CURSOR C1 IS
SELECT CUSTID, SHIPPINGFEE, ORDERCOST 
FROM CustOrder
WHERE OrderID = custOrderID;

BEGIN 
    OPEN C1;
    LOOP 
        FETCH C1 INTO customerID, shipfee, itemscost;
        EXIT WHEN C1%NOTFOUND;

        SELECT CustType INTO customerType
        FROM Customer 
        WHERE CUstID = customerID;
        
        IF(UPPER(customerType)='GOLD' AND itemscost>=100.00) THEN 
            discountPercent := 10;
        END IF;

        discountedCost := (itemscost - (itemscost * (discountPercent/100)));
        grandtotal := (discountedCost + ((taxPercent/100) * discountedCost)) + shipFee;

        UPDATE CustOrder SET 
        TAX = taxPercent,
        DISCOUNT = discountPercent,
        FinalCost = grandtotal 
        WHERE OrderID =  custOrderID;

    END LOOP;
    CLOSE C1;

    RETURN grandtotal;

END;
/