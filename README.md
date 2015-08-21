# Munin for Android Import Export Server
Import/Export script called by [Munin for Android](https://github.com/chteuchteu/Munin-for-Android) Import/Export feature

## Installation
If you want to use your own server for the Import/Export feature, please follow these instructions:

* Clone this repository on your server: `git clone git@github.com:chteuchteu/Munin-for-Android-Import-Export-Server.git`
* Create a new Mysql database on your server (name it as your want, `muninForAndroidImportExport` is a great name)
* *(optionnal)* Create a new Mysql user and give him read/write rights on this database
* Run the `create-tables.sql` script on this database
* Copy the `config-sample.php` file to `config.php`, and set its values depending on the two previous steps
* In Munin for Android, set the **Import/Export server** config item according to your server address*

**Important**: if you install this on your server, please "Watch" this repository to be kept in touch when updates are
made to this script.


(*) Munin for Android 3.5 and up supports import/export server address customization
