--------------------------------------------------------------------------------
CREATE OR REPLACE PACKAGE my_var_pkg AS
-- set up a strongly typed cursor variable for the customer table
  TYPE cust_refcur_typ IS REF CURSOR RETURN customer%ROWTYPE;
  TYPE custorder_refcur_typ IS REF CURSOR RETURN custorder%ROWTYPE;
-- set up a weakly typed cursor variable for multiple use
  TYPE my_refcur_typ IS REF CURSOR;
END my_var_pkg;
/
---------------------------------------------------------------------------------------------

-------------------------------------------------------------------------------------------------------------------------------------
CREATE OR REPLACE PROCEDURE get_cust_info (
  l_custOrderID IN integer, 
  customerName OUT VARCHAR,
  customerID OUT INTEGER,
  customerPhone OUT INTEGER,
  customerStreet OUT VARCHAR,
  customerCity OUT VARCHAR,
  customerZipcode OUT VARCHAR,
  grandTotal OUT FLOAT,
  cust_cursor IN OUT my_var_pkg.my_refcur_typ) 
AS
CURSOR C1 IS 
SELECT C.CUSTID, C.NAME, C.PHONE, C.STREET, C.CITY, C.ZIPCODE, CO.FinalCost
FROM (
    (SELECT *
    FROM CUSTORDER 
    WHERE ORDERID=l_custOrderID) CO
    LEFT OUTER JOIN
    (SELECT CUSTID, NAME, PHONE, STREET, CITY, ZIPCODE 
    FROM CUSTOMER) C
    ON CO.CustID = C.custID
);
BEGIN
  OPEN C1;
    LOOP 
        FETCH C1 INTO customerID, customerName, customerPhone, customerStreet, customerCity, customerZipcode, grandTotal;
        EXIT WHEN C1%NOTFOUND;
        DBMS_OUTPUT.PUT_LINE(customerName);
    END LOOP;
    CLOSE C1;

-- the following returns custorder info based on custorderid
  OPEN cust_cursor FOR SELECT OrderlineItem.itemid,  ITEM.TITLE, OrderlineItem.quantity, DATEOFORDER, SHIPPEDDATE, 
  shippingfee, discount, Tax 
        FROM CustOrder
        JOIN OrderlineItem ON CustOrder.ORDERID = orderlineitem.orderID
        JOIN (SELECT ITEMID, TITLE FROM COMICBOOKS
              UNION
              SELECT ITEMID, TITLE FROM CARTOONMOVIES) ITEM
        ON OrderlineItem.ITEMID = ITEM.ITEMID
Where custorder.orderid =  l_custOrderID;
END get_cust_info;
/
-----------------------------------------------------------------------------------------------------------------------------------------------------
