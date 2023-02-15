-----------------------------------------------------------------------------------------------------
CREATE OR REPLACE PROCEDURE showItemOrdersAfter(l_custID IN integer, 
l_date IN varchar, 
customer_cursor OUT my_var_pkg.my_refcur_typ) 
AS
BEGIN
OPEN customer_cursor FOR 
select CUSTOMER.CUSTID, CUSTOMER.NAME, CUSTOMER.PHONE, CUSTOMER.STREET, CUSTOMER.CITY, CUSTOMER.ZIPCODE,
CUSTORDER.ORDERID, ITEM.ITEMID, ITEM.TITLE, ORDERLINEITEM.QUANTITY, CUSTORDER.DATEOFORDER, CUSTORDER.SHIPPEDDATE,
CUSTORDER.ORDERCOST, CUSTORDER.SHIPPINGFEE, CUSTORDER.DISCOUNT, CUSTORDER.TAX, CUSTORDER.FINALCOST FROM custorder 
JOIN CUSTOMER 
ON CUSTOMER.CUSTID = CUSTORDER.CUSTID
JOIN ORDERLINEITEM 
ON ORDERLINEITEM.ORDERID = CUSTORDER.ORDERID
JOIN (SELECT ITEMID, TITLE FROM COMICBOOKS 
      UNION
      SELECT ITEMID, TITLE FROM CARTOONMOVIES) ITEM 
ON ITEM.ITEMID = ORDERLINEITEM.ITEMID
where custorder.custid = l_custID
AND dateoforder BETWEEN TO_dATE(l_date, 'yyyy-mm-dd') and SYSDATE
AND dateoforder is not null
ORDER BY CUSTORDER.ORDERID, DATEOFORDER;
END;
/
--------------------------------------------------------------------------------------------------------
