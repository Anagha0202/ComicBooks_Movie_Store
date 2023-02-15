CREATE OR REPLACE PROCEDURE computeOrderCost (OIDS IN INTEGER) 
AS 
itemsID OrderLineItem.ItemID%TYPE;
noOfOrdered OrderLineItem.Quantity%TYPE;
lineno OrderLineItem.LineID%TYPE;
prices float;
itemcost float;
orderitemscost float;
CURSOR C1 IS
SELECT ITEMID, QUANTITY, LINEID
FROM OrderLineItem
WHERE OrderID = OIDS;
BEGIN
    orderitemscost := 0.0;    
    OPEN C1;
    LOOP 
        FETCH C1 INTO itemsID, noOfOrdered, lineno;
        EXIT WHEN C1%NOTFOUND;
        SELECT Price INTO prices
        FROM StoreItem 
        WHERE ItemID = itemsID;
        itemcost := noOfOrdered * prices;
        orderitemscost := orderitemscost + itemcost;
    END LOOP;
    CLOSE C1;

    UPDATE CustOrder SET OrderCost = orderitemscost 
    WHERE OrderID = OIDS;
END;
/