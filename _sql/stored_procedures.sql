-- LOGIN STORED PROCEDURE (USE THIS FOR AUTHENTICATION)
DELIMITER //
CREATE PROCEDURE `Try_Login`(
	IN p_email NVARCHAR(50), 
    IN p_password NVARCHAR(50)
)
`whole_proc`:
BEGIN
	-- Set variables
	SET @responseMessage = "";
	Set @p_email = p_email;
	Set @p_password = p_password;
    
    -- Define error handler to set responseMessage in case of error
	BEGIN
		DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		GET DIAGNOSTICS CONDITION 1
			@p1 = RETURNED_SQLSTATE, 
			@p2 = MESSAGE_TEXT;
		SET @responseMessage = CONCAT_WS('Database Error: ', @p1, @p2);
	END;
    
	-- Check if username exists
	PREPARE stmt FROM "select count(ID) > 0 into @userExists from Login where Email_Address = ?;";
	EXECUTE stmt using @p_email;
	DEALLOCATE PREPARE stmt;
	IF @userExists = false THEN -- if username doesn't exist, return error
		SET @responseMessage = CONCAT("ERROR: Username doesn't exist.");
        SELECT @responseMessage;
		LEAVE whole_proc;
	END IF;
	
	-- Check if password is correct
	PREPARE stmt FROM "select count(ID) > 0 into @passwordCorrect from Login where Email_Address = ? and 
	password = UNHEX(SHA2(CONCAT(?, Salt), 512));";
	EXECUTE stmt using @p_email, @p_password;
	DEALLOCATE PREPARE stmt;
	IF @passwordCorrect = false THEN  -- if password isn't correct, return error
		SET @responseMessage = 'ERROR: Incorrect Password.'; 
        SELECT @responseMessage;
		LEAVE whole_proc;
	END IF;
	
	-- If the procedure gets this far, then the login was successful.
     SET @responseMessage = 'Success.'; 
	 SELECT @responseMessage;
END //


-- ADD NEW USER STORED PROCEDURE (USE THIS FOR CREATING A USER -- IT AUTOMATICALLY SALTS AND HASHES PASSWORD)
DROP PROCEDURE IF EXISTS Add_Account;
DELIMITER //
CREATE PROCEDURE `Add_Account`(
	IN p_email NVARCHAR(200),
    IN p_first_name NVARCHAR(50),
    IN p_last_name NVARCHAR(50),
    IN p_password NVARCHAR(50),
	IN p_account_type NVARCHAR(50),
    IN p_store_name NVARCHAR(50)
)
`whole_proc`:
BEGIN
    SET @responseMessage = "";
    SET @p_account_type = p_account_type;
    -- check to see if account type is valid
    IF @p_account_type != "A" AND @p_account_type != "M" THEN
        SET @responseMessage = CONCAT("ERROR: Invalid account type.");
        SELECT @responseMessage;
        LEAVE whole_proc;
    END IF;
    SET @p_email = p_email;
    -- Check if username exists
    PREPARE stmt FROM "select count(id) > 0 into @userExists from the_booth.Account where email = ?;";
    EXECUTE stmt using @p_email;
    DEALLOCATE PREPARE stmt;
    IF @userExists = 1 THEN -- if username exists, return error
        SET @responseMessage = CONCAT("ERROR: Email already taken.");
        SELECT @responseMessage as "Response";
        LEAVE whole_proc;
    END IF;
    BEGIN 
        DECLARE EXIT HANDLER FOR SQLEXCEPTION  -- Define an error handler to set the responseMessage variable in case of error
            GET DIAGNOSTICS CONDITION 1
                @p1 = RETURNED_SQLSTATE, 
                @p2 = MESSAGE_TEXT;
            SET @responseMessage = CONCAT_WS('Database Error: ', @p1, @p2);
        -- set variables
        SET @p_first_name = p_first_name;
        SET @p_last_name = p_last_name;
        SET @p_store_name = p_store_name;
        SET @p_password = p_password;
        SET @salt = UUID();
        SET @bin_salt = UUID_TO_BIN(@salt);
        SET @passhash = UNHEX(SHA2(CONCAT(@p_password, @salt), 512));  -- Hash the password with salt and convert to binary
        PREPARE stmt FROM "INSERT INO the_booth.Account (email, first_name, last_name, password, salt, type, store_name, created) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        EXECUTE stmt using @p_email, @p_first_name, @p_last_name, @passhash, @bin_salt, @p_account_type, @p_store_name;
        DEALLOCATE PREPARE stmt;
		-- Set responseMessage variable to Success if the insert was successful
        SET @responseMessage = 'Success.'; 
        SELECT @responseMessage;
    END;
END //
DELIMITER ;

call Add_Account("fisheral@kean.edu", "Alexander", "Fisher", "test123", "M", "Nike");

select * from the_booth.account;