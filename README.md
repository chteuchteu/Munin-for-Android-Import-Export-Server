# Munin for Android Import Export Server
Import/Export script called by [Munin for Android](https://github.com/chteuchteu/Munin-for-Android) Import/Export feature

## Installation
If you want to use your own server for the Import/Export feature, please follow these instructions:

- On your web server
    ```bash
    # cd to destination
    cd /var/www/
    
    # Clone this project
    git clone https://github.com/chteuchteu/Munin-for-Android-Import-Export-Server.git && cd Munin-for-Android-Import-Export-Server
    
    # Install it
    composer -n install --optimize-autoloader
    
    # Update app/config/parameters.yml (database_name, database_user, database_password)
    vim app/config.parameters.yml
    
    # Create database & its schema
    php bin/console -v --env=prod doctrine:database:create
    php bin/console -v --env=prod doctrine:schema:update --force
    
    # Clear symfony cache
    php bin/console -v --env=prod cache:clear
    
    # Configure a vhost for this project
    ```

- In Munin for Android:
    Set the **Import/Export server** config item according to your server address*

**Important**: if you install this on your server, please star + watch this repository to be kept in touch when updates are
made to this script.
