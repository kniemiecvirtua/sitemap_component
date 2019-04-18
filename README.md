Sitemap Import Component

Install by executing the following command:
composer require "kniemiecvirtua/sitemap-component":"dev-master"

Upload your sitemap file in the: "your_website_root/var/import/" directory

How to import:
php console.php import_sitemap sitemap_file website_name user_id

Example usage:
php console.php import_sitemap sitemap.xml Sitemap1 5
