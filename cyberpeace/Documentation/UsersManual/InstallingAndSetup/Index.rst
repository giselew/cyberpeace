.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _users-manual-installing-and-setup:

Installing and Setup
--------------------

#.     Install the extension in EM.
#.     Add Static TypoScript Template to TS-Setup
#.     Create a sysFolder for the backend data (IPs and log) and assign its id as storagePid in tx_cyberpeace_pi1 setup
#.     (Optional) For local blocklist and imported blocklist from Spamhaus please install extension *toctoc_comments*. In *toctoc_comments* backend module you can import and refresh the Spamhaus IP-blocklist
       set activateBanBlockByLocalIPBlockTables = 1, activate blocking by .htaccess if you want or change values for blockByLocalIPBlockTables.RedirectPageStatic and blockByLocalIPBlockTables.RedirectPageLocal
#.     (Optional) Get an account on abuseipdb.com, register as webmaster and verify your website.
       set activateBanBlockByAbuseipdbCom = 1 and add your APIkey to blockByAbuseipdbCom.abuseipdbComAPIId
       
In the default setup crawlers and spiders are blocked unless they don't contain 
Google,Yahoo,LinkedIn,msnbot,bingbot,Facebook,Facebot,DuckDuck,Twitter or ia_archiver 
in their user agent string
