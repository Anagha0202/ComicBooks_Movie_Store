CREATE OR REPLACE PROCEDURE setShippingDate(
    custOrderID in INTEGER, 
    shippingDate IN VARCHAR)
AS
	BEGIN
		UPDATE CUSTORDER
		SET SHIPPEDDATE = TO_DATE(shippingDate, 'dd-mm-yyyy')
		WHERE ORDERID = custOrderID;
	END;
/