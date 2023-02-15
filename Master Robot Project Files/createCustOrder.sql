CREATE OR REPLACE FUNCTION createCustOrder(custID IN integer)
RETURN integer 
AS 
    orderID integer;
    countID integer;
    low integer;
    high integer;
    
    BEGIN
        SELECT COUNT(*) INTO countID 
        FROM generateID;

        IF countID=0 THEN 
            SELECT MAX(OrderID) INTO low
            FROM CustOrder;
            low := low+1;
            high := low+1000;
            INSERT INTO generateID 
                SELECT * FROM 
                    (WITH bnd AS 
                        (SELECT low lo, high hi FROM DUAL) 
                        SELECT (SELECT lo from bnd) - 1 + LEVEL r 
                        FROM DUAL CONNECT BY LEVEL <= (SELECT hi-lo FROM bnd)); 
        END IF;

        SELECT R INTO orderID 
            FROM (SELECT * FROM generateID ORDER BY DBMS_RANDOM.VALUE) WHERE ROWNUM=1;

        INSERT INTO CustOrder (OrderID, CustID) VALUES (orderID, custID);

        DELETE FROM generateID WHERE R=orderID;

        RETURN orderID;

    END;
    /