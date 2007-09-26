   ####################################################################
   ##                P R O J E C T A R C H E T Y P E                 ##
   ##                 www.fuzzywoodlandcreature.net                  ##
   ####################################################################

#  Creates a simple table to store users for the user component
   
   CREATE TABLE A_users
      (
         id int(11) unsigned NOT NULL auto_increment,
         email varchar(255) collate utf8_unicode_ci NOT NULL,
         password_hash varchar(48) collate utf8_unicode_ci NOT NULL,
         permissions text collate utf8_unicode_ci NOT NULL,
         active enum('true','false') collate utf8_unicode_ci NOT NULL default 'false',
         first_name varchar(255) collate utf8_unicode_ci NOT NULL,
         last_name varchar(255) collate utf8_unicode_ci NOT NULL,
         PRIMARY KEY (id),
         UNIQUE KEY email (email),
         KEY password_hash (password_hash)
      );
