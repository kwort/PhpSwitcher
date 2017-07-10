XamppSwitcher
=============

Script for use multiple version xampp with same folder name with 2 digit version suffix, a.e :

	/opt/lampp
	/opt/lampp54
	/opt/lampp56
	/opt/lampp71

No version folder is a symblink of current version

Installaton
-----------

sudo su
cd /opt
git clone
chmod a+x /opt/XamppSwitcher/xamppSwitcher.php
ln -s /opt/XamppSwitcher/xamppSwitcher.php /usr/bin/xamppSwitcher

Usage
-----

xamppSwitcher VERSION [XAMPP_ARGUMENTS]