CREATE OR REPLACE PROCEDURE updateCustType(customerID IN integer)
AS 
BEGIN
UPDATE Customer SET CustType=UPPER('GOLD') 
WHERE CustID= customerID;
END;
/