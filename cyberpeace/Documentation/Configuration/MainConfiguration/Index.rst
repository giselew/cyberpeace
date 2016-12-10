.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-main-configuration:

Main configuration
------------------

In main configuration, you set up the folder where CyberPeace (storagePid) data comes.

Here you activate or deactivate the 4 blocking components (crawlers, block lists, abuseipdb and syslog).

More options are for the handling of IPs and Log-entries regarding retain time, deletion und unblock (jail-) time, 
the frequency of .htaccess maintenance defines also here.

Additionally you have some setup-only options, which help to debug if necessary

===========================================  ==========  =====================================================  =======================
**Property:**                                Data type:  Description:                                           Default:
===========================================  ==========  =====================================================  =======================
storagePid                                   int+        Id of the sysFolder for the CyberPeace IP-list 
                                                         and log data
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
activateBanBlockByCrawler                    boolean     IP-blocking of crawlers, spiders and bots:             1
                                                         Activate banning if IPs user-agent represents an
                                                         unwanted crawler, spider or bot
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
activateBanBlockByLocalIPBlockTables         boolean     IP-blocking with IP-blocklist by spamhaus.org or       0
	                                                 local IP blockList: 
	                                                 Activate banning if IP is present in blocklists 
	                                                 of EXT:toctoc_comments
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
activateBanBlockByAbuseipdbCom               boolean     IP-blocking with IP-blocklist by abuseipdb.com:        0
	                                                 Activate banning if IPs present in abuseipdb.com 
	                                                 (requires API-Id)
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
activateBanBlockBySysLog                     boolean     IP-blocking of bad IPs found in syslog:                1
	                                                 Activate banning if IP is found in syslog and has 
	                                                 a match with ban patterns 
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
unblockAfterDays                             int+        Jailtime for blocked IPs:                              7
                                                         Days after an IP has been blocked an unblock 
                                                         will be allowed                
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
deleteUnusedUnblockedIPsAfterDays            int+        Retain delay for unlocked IPs:                         10
                                                         Days after an unused unblocked IP will be deleted 
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
recheckHtaccessForUnblockAfterMinutes        int+        Logtime interval: Log will be written and              120
                                                         .htaccess will be maintained after a 
                                                         minimal interval of this number of minutes 
                                                         for IPs to unblock or delete 
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
writeLogOnrecheckhtaccess                    boolean     Enable Log: You can deactivate creation                1
                                                         of log-entries in table tx_cyberpeace_log, 
                                                         only .htaccess will be maintained 
 
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
deleteOldLogAfterDays                        int+        Log Maintenance: During maintenance of .htaccess       150
                                                         old log-entries in table tx_cyberpeace_log are 
                                                         deleted if they are older than this number of days
-------------------------------------------  ----------  -----------------------------------------------------  -----------------------
recheckGoodIPAfterHours                      int+        Good IPs recheck delay:                                12
                                                         Hours after a returning good IP rechecks
-------------------------------------------  ----------  -----------------------------------------------------  ----------------------- 	
deleteUnusedGoodIPsAfterDays                 int+        Good IPs drop delay:                                   7
                                                         Days after last check when a good IP will drop from
                                                         the database, if it is not returning to the site
-------------------------------------------  ----------  -----------------------------------------------------  ----------------------- 	
**Options setup-only**                                                        
-------------------------------------------  ----------  -----------------------------------------------------  ----------------------- 	
recheckHtaccessUnblockAll                    boolean     During recheck of .htaccess, consider all IPs that     0
                                                         can unblock, regardless of last runtime of the 
                                                         .htaccess-check (more time consuming but useful if 
                                                         problems occured)
-------------------------------------------  ----------  -----------------------------------------------------  ----------------------- 	
noCheckDevIPMask                             boolean     Do not check IPs associated with DevIPMask             1
-------------------------------------------  ----------  -----------------------------------------------------  ----------------------- 	
testip                                       string      You can test how the system reacts on a given 
                                                         IP for test purposes
-------------------------------------------  ----------  -----------------------------------------------------  ----------------------- 	
testuseragent                                string      You can test how the system reacts on a given 
                                                         user agent for test purposes (Crawler)
===========================================  ==========  =====================================================  =======================

