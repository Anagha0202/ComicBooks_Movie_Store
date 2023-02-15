CREATE OR REPLACE TRIGGER changeCustType
	AFTER 
        UPDATE OF CUSTTYPE 
    ON customer 
	FOR EACH ROW 
	WHEN (NEW.CUSTTYPE = 'GOLD')
DECLARE 
	ordersid integer;
	grandtotal FLOAT;
BEGIN 
   IF UPDATING THEN
    	UPDATE CUSTORDER 
	        SET SHIPPINGFEE = 0.0
	        WHERE CUSTID = :NEW.CUSTID AND SHIPPEDDATE > SYSDATE;	
   END IF;
END; 
/