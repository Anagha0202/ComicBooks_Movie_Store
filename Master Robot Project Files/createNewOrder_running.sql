ACCEPT CustomerID NUMBER PROMPT 'Enter the customer ID: '

DECLARE 
r_OrderID CustOrder.OrderID%type;
c integer :=1;
r_errorMessage varchar(200);
r_grandTotal float;
BEGIN
    r_OrderID := createCustOrder(&CustomerID);
    DBMS_OUTPUT.PUT_LINE('Your OrderID is: '||r_OrderID);

    createOrderLineItem(r_OrderID, 'CB001', &CustomerID, SYSDATE, 1, NULL, c, r_errorMessage);
    IF r_errorMessage IS NOT NULL THEN 
        DBMS_OUTPUT.PUT_LINE(r_errorMessage);
    ELSE 
        setShippingDate(r_OrderID, (SYSDATE+2));

        computeOrderCost(r_OrderID);

        r_grandTotal := computeTotal(r_OrderID);
    END IF;
    c:=c+1;
    r_errorMessage:= NULL;
    createOrderLineItem(r_OrderID, 'CM001', &CustomerID, SYSDATE, 1, NULL, c, r_errorMessage);
    IF r_errorMessage IS NOT NULL THEN 
        DBMS_OUTPUT.PUT_LINE(r_errorMessage);
    ELSE 
        setShippingDate(r_OrderID, (SYSDATE+2));

        computeOrderCost(r_OrderID);

        r_grandTotal := computeTotal(r_OrderID);
    END IF;
    DBMS_OUTPUT.PUT_LINE('The grand total='||r_grandTotal);
END;
/