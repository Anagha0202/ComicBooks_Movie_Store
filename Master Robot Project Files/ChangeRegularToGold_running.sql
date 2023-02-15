ACCEPT customerID NUMBER PROMPT 'Enter customerID to change the membership to Gold: ';

 BEGIN
	updateCustType(&customerID);
	computeTotalOnCustType(&customerID); 
END;
/