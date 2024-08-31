
Some code examples for testing the POST and webhook options in Jotform.

### Software used:
- [XAMPP](https://www.apachefriends.org/download.html)
- [ngrok](https://ngrok.com/) (needs sign-up)


### Example forms that can be cloned:


- [POST to Echo](https://www.jotform.com/242423137119955)
- [POST to SQL](https://www.jotform.com/242422471288962)
- [Webhook to .txt](https://www.jotform.com/242423323923955)
- [Webhook to SQL](https://jotform.com/form/242424133952957)

[How to clone from URL](https://www.jotform.com/help/42-how-to-clone-an-existing-form-from-a-url/)

### General steps:
(Done on Windows in this case)

- Open XAMPP and start Apache & MySQL
- Open the Admin page for MySQL and set up a database to use
    - [Guide for MySQL setup with XAMPP](https://www.geeksforgeeks.org/how-to-create-a-new-database-in-phpmyadmin/)
    - In my case I added all columns as VARCHAR to keep it simple

      ![tablesetup](https://github.com/user-attachments/assets/17e83cd6-009e-45bf-a932-3158c324aab4)


- Download PHP files from this repository
- Place files in the `htdocs` folder
    - Default location is `C:\xampp\htdocs`

- Start ngrok with `http {port}`
    - In my case `.\ngrok.exe http 80`
- Save the generated ngrok link
    - Usually looks something like `https://{random characters}.ngrok-free.app`
- Test that link works by adding one of the filenames at the end and open it in a browser
    - Example: https://{random characters}.ngrok-free.app/print_and_save_sql.php
    - It should show an error about submission data if the link works

### Steps for forms:
#### For POST to SQL:
Set Thank You page to redirect to your webhook_to_text.php file with POST data.

    https://{random characters}.ngrok-free.app/print_and_save_sql.php

#### For Webhook to .txt:
In form integrations, add webhook and link to your webhook_to_text.php file.

    https://{random characters}.ngrok-free.app/webhook_to_text.php

#### For Webhook to SQL:
In form integrations, add webhook and link to your webhook_to_sql.php file.

    https://{random characters}.ngrok-free.app/webhook_to_sql.php
