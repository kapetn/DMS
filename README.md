DMS
===

Data Management System

15/07/2013 - version 3.3.3
-------------------------------------------
    Added "greater or equal than" operator in Search Form
    Added "less or equal than" operator in Search Form
    Added "greater or equal than" operator control in datahandler.php
    Added "less or equal than" operator control in datahandler.php


15/06/2012 - version 3.3.2
-------------------------------------------

    Added ticket system database tables. The system sends email to administrators with ticket message. Also the logged in user's ID is stored in equivalent table column to now who created the ticket.
    Added new attribute in tables repository to distinguish simple drop down lists with multiple drop down.

14/06/2012 - version 3.3.1
-------------------------------------------

    added display as text attribute for varchar columns in tables repository
    added display as text in txt columns. Disable FCKeditor.

13/06/2012 - version 3.3.0
-------------------------------------------

    corrected keeping GET page parameter in pagination when user selects ORDER BY column.
    Added splash page system setting.
    Ajax check duplicate values added in vrc_ columns (insert-new mode).
    Forgot password recovery procedure added. The user must type username and email and the new password will be sent to his/her email.


//$version = "version 3.1.9"; /* 14/05/2012 Last 50 tasks link added in Contacts, Flags icons for Greek and English language added, direct link relating New Contact with existing Company added*/
//$version = "version 3.2.1"; /* 16/05/2012 Email via CRM, direct link to Search Form tab, bug user assigned to CRM module corrected, bug display recipient email value corrected*/
//$version = "version 3.2.2"; /* Add file attachment in Contact Messages module*/
//$version = "version 3.2.3"; /* Project module was added. Relationship between Project and Contact */
//$version = "version 3.2.4"; /* Export to MS Excel feature added. Add settings database table and features in CRM. */
version 3.2.5 -> System Announcement feature was added. JQuery theme feature in system settings was added. (23/05/2012)
version 3.2.6 -> Display "send email" link only in those fields that have an email value. Added GET parameters in pagination link URL to keep the order by clause if selected. FCKeditor basic buttons after Kostas suggestion. Added in Search form Date From - Date To range feature. All date fields must be of DATE datatype and not DATETIME to function well. Added Back button. Display "View Page" only when the equivalent fields have a value. Added new record button at the right side of int_ form fields. (24/05/2012)
version 3.2.7 -> Added record locking. Added server date time above logout button.
11/06/2012 -  version 3.2.8 -> Added multiple select object. All foreign key table columns (e.g. int_columnname) must be altered to VARCHAR(255) datatype. Multiple integer values can now be stored in varchar datatype columns (e.g. 1,3,5,8)

12/06/2012 - version 3.2.9 -> Added search in multiple values. Changed various links with icons (e.g. send email, view url etc)




