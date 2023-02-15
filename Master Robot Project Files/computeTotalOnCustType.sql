CREATE OR REPLACE PROCEDURE computeTotalOnCustType(customerID IN INTEGER)
AS
ordersID CUSTORDER.ORDERID%TYPE;
CURSOR C1 IS
SELECT ORDERID
FROM CUSTORDER 
WHERE CustID = customerID
AND SHIPPEDDATE > SYSDATE;
grandtotal FLOAT;
BEGIN
    OPEN C1;
    LOOP 
    FETCH C1 INTO ordersID;
    DBMS_OUTPUT.PUT_LINE('ORDERID IS'||ordersID);
    EXIT WHEN C1%NOTFOUND;
    grandtotal := computeTotal(ordersid);
    DBMS_OUTPUT.PUT_LINE('GRANDTOTAL='||grandtotal);
    END LOOP;
    CLOSE C1;
END;
/