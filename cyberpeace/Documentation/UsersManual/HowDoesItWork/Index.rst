.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _users-manual-how-does-it-work:

How does it work?
-----------------

When TYPO3 renders a page in frontend there are several hooks available to place additional code,

*CyberPeace* uses the hook **checkDataSubmission**

This is the hook, which is available just before page generation

.. figure:: /Images/hookcheckdatasubmission.png
  
*CyberPeace* is placed in this hook and when it comes into action, it quickly checks the database for the IP of the client. 

If it's a good IP and already present in the IPList of CyberPeace, then the last time when the IP accessed is checked against option recheckGoodIPAfterHours.

If the IP is not found in the IPList or recheckGoodIPAfterHours requires a recheck, then the IP is checked 

- First: Crawler check (works on TypoScript-Options)

- Second: IP-black-lists by *toctoc_comments* (if present)

- Third: Live IP-black-lists by `www.abuseipdb.com <https://www.abuseipdb.com/>`__ (you need an API-Key and need to verify your website as webmaster - it's easy)

- Possible fourth step is a quick batch step: Finally, all 20 minutes (default of option recheckSyslogAfterMinutes) the TYPO3 syslog is checked for error messages which are triggered when hackers try to access the site. Up now, we identified two patterns in detailmessages in the syslog as "hacker-traces", see default of option syslogIdentificationList.

- Possible fifth and last step is another batch: .htaccess is maintained for IPs which are unblocked and released from jail all 2 hours (option recheckHtaccessForUnblockAfterMinutes = 120)


Now if a test marks an IP as bad IP, then the IP is added to .htaccess and the client is redirected to the homepage - resulting in an error-message "403 - Forbidden" - it's the normal error-message by your web server.
If a test with the IP-black-list marks the IP as blocked for the frontend, then the IP could also be redirected to a specific page on your site or any webpage you want.