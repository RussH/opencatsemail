This will be a new oauth2 authentication for imap based on th eolder code in this repo, Ill look to rework it to use 'native' opencats activities rather than a custom email table. Will try out Codex and see what it makes of this. 
# Installing
Navigate to the folder on your server where you want to run the script.

1. Upload the files:
* application.php
* class.php
* config.php
* mimeDecode.php

2. Create a folder called "files" and make sure it's writable by the web server.

3. In your MySQL database, create the tables "emails" and "files" by importing the file:
* mysql-structure.sql

4. Run `composer install` to install required dependencies. The mini-app now
   relies on `stevenmaguire/oauth2-microsoft` for OAuth2 support.

5. Create a `.env` file in the project root and set the following variables:

   ```
   MYSQL_HOST=
   MYSQL_USER=
   MYSQL_PASS=
   MYSQL_DB=
   IMAP_HOST=
   IMAP_USER=
   OAUTH_CLIENT_ID=
   OAUTH_CLIENT_SECRET=
   OAUTH_TENANT_ID=
   OAUTH_REFRESH_TOKEN=
   FILE_STORE=files
   GRAB_TYPE=fetch
   ```

   The `.env` file is excluded from version control via `.gitignore`.

6. Optionally adjust advanced options such as GRAB_TYPE in `config.php`.

# Pipe vs Fetch
You can either pipe an email address to the script to process each email as it arrives, or you can fetch emails one-by-one from a mailbox using a cron job (emails are deleted as they're processed.) We recommend using the fetch method.

The script is set to "fetch" by default but you can change it to "pipe" in config.php

# IMAP Flags
There are flags set by default in config.php but you can change them by referencing:

http://php.net/manual/en/function.imap-open.php

# Running the script
The file to either pipe to or run as a cron job is application.php

# Referencing attachments/images
When an email is processed, a unique ID will be generated for the MySQL record and if any attachments are present, a folder for that ID will be created in your "files" folder. Attachments will be saved there.

Reference to images is only saved in the "text-html" part of the message, and not the "text-plain."

Images are referenced like:

[filePath]/dkvmbY14NZr4l4eb79Gs1513724817/mypicture.png

When including the message in your own application you'll simply replace "[filePath]" with the relevant folder path, for example, if the path to your files is:

[example.com]/application/files/dkvmbY14NZr4l4eb79Gs1513724817/mypicture.png

then your code will be

$str = str_replace("[filePath]","/application/files",$str);
