=================================
Invitation Codes in eZ Publish 4
=================================

This extension provides an easy way to add invitation codes to your user accounts
in eZ Publish, preventing users from registering unless they have an existing
invitation code. 

The extension provides a data type that will not validate without a valid invitation 
code. It also provides an admin module for managing user invitation codes. 


INSTALLING
=================================

To install this extension, do the following:

1. Copy the blendinvitationcode folder into your eZ Publish extensions directory

2. Run the included schema.sql file against your database (sql/mysql/schema.sql)
   This will create a table, 'blend_invitations', for storing generated 
   invitation codes.
   
3. Add the extension to your settings/override/site.ini.append.php file in the 
   ActiveExtensions list, e.g.
   <code>
   [ExtensionSettings]
   ActiveExtensions[]
   ActiveExtensions[]=blendinvitationcode
   ActiveExtensions[]=ezjscore
   ...
   </code>
   
4. Run the autoloads generator to add the extension's classes to eZ's autoload list.
   <code>
   bin/php/ezpgenerateautoloads.php
   </code>
   
5. Clear the eZ Publish cache.
   <code>
   bin/php/ezcache.php --clear-all
   </code>
   
CONFIGURING
===================================

To start using invitation codes, do the following: 

1. (optional) If a user besides the admin will be managing invitation codes, 
   configure the 'invitation' policy in the Roles and Policies administrator for
   the users that will be managing the invitations.
   
2. Edit the User class in the Class Editor to include the 'Invitation Code' data 
   type. This will add the field to user registration forms and require users
   to supply an invitation code.
      
3. Click 'Invitation Codes' in the sidebar of the 'Users' tab in the eZ Publish
   administration interface to create new invitation codes.
   
