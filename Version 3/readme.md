# Installation

## On an Empty System in Webspace

### Preparations
1. Download the latest version of the admin panel (e.g. Version 3.1).
2. Make sure that **PHP 8.3 or higher** is installed.

### Process
1. Go to the directory in your webspace that is linked to the **/** route.
2. Upload the downloaded version to this directory.
3. Open the **/** route in your browser and log in using the default credentials.
4. We will set up everything for you.

---

## On an Existing System in Webspace

### Preparations
1. Download the latest version of the admin panel (e.g. Version 3.1).
2. Make sure that **PHP 8.3 or higher** is installed.

### Process
1. Go to the directory in your webspace that is linked to the **/** route.
2. Back up all existing files on your system, then delete them from the webspace.
3. Upload the downloaded version to this directory.
4. Open the **/** route in your browser and log in using the default credentials.
5. We will set up everything for you.
6. Upload your saved files to **/f/pages/main/** (you might need to update asset URLs if they were not imported as relative paths; make sure the index file is named **index.php**).

---
## On Apache 2

### Preparations
1. Download the latest version of the admin panel (e.g. Version 3.x.1).
2. Ensure that **PHP 8.3 or higher** is installed.
3. Make sure Apache is installed and running.

### Process
1. Go to your Apache **document root**, e.g., `/var/www/html`.
2. Back up any existing files in the directory, then delete them.
3. Upload the downloaded admin panel version to this directory.
4. Create or edit an Apache site configuration in `/etc/apache2/sites-available/`, e.g., `admin.conf`:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/admin_error.log
    CustomLog ${APACHE_LOG_DIR}/admin_access.log combined
</VirtualHost>
```
Enable the site and reload Apache:

```
sudo a2ensite admin.conf
sudo systemctl reload apache2
```

- Open your site in a browser (http://your-domain.com/) and log in using the default credentials.
- Upload your saved files to /var/www/html/f/pages/main/.
- Update asset URLs if they were not imported as relative paths.
- Make sure the main index file is named index.php.

---

# Upgrade

## From Version 2

1. Edit your `src/.htdatabase/.htsettings.txt` file — change the single line (e.g. `/html/`) to a two-line format, for example:  

`/f/cloud/
/f/pages/`

   Inside the **cloud** directory, place your old `/html` files in the following subdirectories:
- `audio/` (audio files)
- `docs/` (applications)
- `files/` (scripts, text, plain files)
- `image/` (images)
- `video/` (videos)
- and sort all existing files accordingly.

2. Set permissions for the first path (`/f/cloud/`) and its subdirectories to **0700** — **not** for the second line (`/f/pages/`) or its subdirectories!
3. Create the directory **/f/pages/main/** and copy your old root files there.
4. Delete all files in the root **/** directory.
5. Create a file named **index.php** and paste the code from `class/.htUserClass.php`, lines 171–219 (from `<?php` to the closing `}`).
6. Replace all **class** files with the new ones.
7. Replace all **src/layout**, **src/sides**, and **src/system** files with the new versions.
8. Create an empty file named `src/.htdatabase/.htshares.txt`.
9. Log in with your old credentials — and you’re done!
