# Readme file : Blog.


This project will help you to create a mini blog, with an administration. 
It respects the main protections' rules like csrf attac, SQL injections and so on. 

###Configuration

To install your project, you have to copy these files. 

- Click on "clone or download" : 
	If you choose 'Open in Desktop', you will upload these files directly from github, by GitHub Desktop (from example)
	If you choose to copy these files in .zip, 

- Then you launch a composer install and you run your project locally. 

- You have to configure a Constants file in App\Config : 
namespace App\Config;
    class Constants{
        CONST DOCUMENT_ROUTE = STRING ;
        CONST MAIN_URL = STRING;
        CONST DSN =  STRING;
        CONST USER_NAME_DB = STRING;
        CONST PASSWORD_DB = STRING;
        CONST MAIL_TO = STRING;
        CONST MAIL_FROM = STRING;
        CONST COOKIE_LIFE_TIME = INTEGER;
    }

- If you choose to test this project in local mode : 
	You have to put the TinyMCE module in your public folder, 
	You have to configurate your SMTP parameters. In that case, you could send email. 

###Usage
These files allow you to create quickly a mini blog. If you want to put some modifications, fell free to change some files. 
- For changing the background image : 
You will have to rename the src ligne in wiews/templates/layoutFront
- For modifying some contents in html : 
Each page is independant. So you can modify the aspect of the page. However, you can't modify the main structure of the site, and ask to get some articles in a page that have any. 
In that case, you will have to get these files by change the route or add a logic in the controller.
-Creating a new user : for the first use of this application, you will have to create a user in the database. Don't forget to put a password crypted. Another solution is to configurate your email generator (in local) and to click on "mot de passe oublié". 
In that case, the system will send you a new password, and you will be allowed to change this password.
-For adding a new article : You can add a lots of articles in you database. Fill all fields and save the datas. 
