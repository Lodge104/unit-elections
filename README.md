Order of the Arrow Virtual Unit Elections Portal
=========

This is the Unit Elections Portal used by Occoneechee Lodge. This system allows for our lodge to track unit elections from start to finish.

Our main website allows for unit leaders to request a unit election via a form that sends an election request to a Chapter Adviser. The Chapter Adviser has an account on our unit elections portal and logs the request. The Chapter Adviser then responds to the request via email and gives the Unit Leader an access key provided by the system and a link to the unit leader page within this unit elections portal. 

The Unit Leader can then access the portal and put in all the vital information about the election candidates. 

Once the time comes for the unit election, the election team can turn on voting within the unit election portal and give a completely anonymous voting link to the Scouts within the unit. Once a scout vote, the system leaves a cookie on their device preventing them from voting more then once. 

After voting is completed, the unit election team turns off voting and the election results are available immediately. The unit leader, election team, and lodge leadership all have access to view the results. Only lodge leadership with the Admin privilage have the ability to export the results.

##### Features

- User Management
- Automated Election Results
- Anonymous Voting
- Unit Leader Dashboard with pre-election access
- LodgeMaster Exporting

Technologies used:
------------------
##### Prerequisites

- `PHP` *_required_*
	- Minimum version: `7.0`
	- `pdo_mysql` extension required
	- Recommended to enable `shell_exec`

- `MySQL` *_required_*
	- Version `5.6+` recommended

- `Composer` *_required_*
	- Version `1.2.1+` recommended
	- `mbstring` and `dom` php extensions required

	 <small>*If Composer is not installed on the system or accessible through `shell_exec`, a self-contained `composer.phar` file located in the `install` directory is used*</small>

- `cURL` _recommended_
	- Version `7+` recommended

##### Components loaded via Composer
- `jQuery`
	- Version `3.1`
	- Pulled in via composer
- `Bootstrap`
	- Version `^3`
- `PHP-Mailer`
	- Version `5.2`
- `JSON Web Tokens` (JWT) (Firebase implementation)
	- Version `5.0`

##### Other libraries
- [PHP Login for User Management](https://github.com/therecluse26/PHP-Login)
    - Version `3.1`
- [Originally Forked from eibrown12](https://github.com/eibrown12/unit-elections)
- `DataTables`
	- Version `1.10.16`
- `Cropper`
- `LoadingOverlay`
- `Multiselect`
	- Version `2.5.0`

##### General Recommendations

- Enable SSL on your site! [Get a free cert at LetsEncrypt](https://letsencrypt.org)
	 - Their free tool [Certbot](https://certbot.eff.org) makes this process virtually painless

- Linux server running [Apache](https://www.apache.org) or [Nginx](https://nginx.org) is preferred

- Shell access is recommended. While it is likely possible to install this library without shell access (such as on a shared web hosting provider), this is unsupported. It's highly recommended that you instead opt for a VPS provider such as [DigitalOcean](https://m.do.co/c/da6f17522df3) that allows you root shell access

- Run `mysql_secure_installation` on server prior to app installation

- Host your database on an encrypted filesystem

- File/directory permissions should be locked down to an appropriate level
	- [Useful information](https://www.digitalocean.com/community/tutorials/linux-permissions-basics-and-how-to-use-umask-on-a-vps#types-of-permissions)

Installation
------------

#### Clone the Repository
	$ git clone https://github.com/Lodge104/unit-elections.git

#### Install necessary dependencies with `Composer`
	$ composer install --no-dev

Run Composer using SSH in the root directory that has the file `composer.json`. To use this repository, your website hosting service mush allow SSH. If you do not already have Composer installed with your hosting service, you can run the `composer.phar` file in the /install/ directory using the following command while your still in the root directory. Change /path/to/ to whatever your root is.

    $ php /path/to/install/composer.phar install --no-dev

#### Run through web-based installer
Open this link in your web browser (replacing [yoursite.com] with your site address)

    http://{yoursite.com}/install/index.php

Select an installation option from the pop-up modal that appears: `Automated` or `Manual`


[Automated Installation Instructions](docs/install_automated.md)

[Manual Installation Instructions](docs/install_manual.md)

#### Install Unit Elections Database
Find the `unitelections.sql` file in the root of this repository and import it into your MySQL database. This will create a new database called `elections`. This will keep your data seperate from user management data.

Then find the `unitelections-info-sample.php` file and rename it to `unitelections-info.php` leaving it in the site's root. Change the values within the file to match the `elections` database you just created.


Documentation
-------------
[Site Config Settings](docs/site_config.md)

[API Methods](docs/methods.md)

\* *Full API documentation can be found by nagivating to:* `{yoursite.com}/docs/api/index.html`

[Change Log](docs/changelog.md)
