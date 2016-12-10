.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-block-by-local-i-p-block-tables:

Block by local IP block tables
------------------------------

In section blockByLocalIPBlockTables, you set up options regarding blocking of spiders, bots and crawlers.

**Note:** options in the constants have not always the same name as the options in setup.

In column Property the corresponding value of the *constant* is added after the setup property if it differs.

=================================  ==========  =====================================================  ===============================
**Property:**                      Data type:  Description:                                           Default:
=================================  ==========  =====================================================  ===============================
RedirectDeny                       boolean     Use redirect or 403?:                                  0
*RedirectDenyIPBlockTables*                    If you want redirect using 403-HTTP-Code (access 
                                               forbidden and .htaccess) set this to 1 
                                               (if set to 1, RedirectPageStatic and -Local 
                                               have no more effect)    
---------------------------------  ----------  -----------------------------------------------------  -------------------------------
RedirectPageStatic                 string      Page (TYPO3 page-id) or URL where to redirect banned   http://www.hell.com/
                                               or URL where to redirect banned IPs from 
                                               spamhaus.org IP-blocklist
---------------------------------  ----------  -----------------------------------------------------  -------------------------------
RedirectPageLocal                  string      Page or URL where to redirect banned IPs               http://www.xn--hlle-5qa.de/
                                               (from the local IP-blocklist)
---------------------------------  ----------  -----------------------------------------------------  -------------------------------
blockForever                       boolean     block IP forever: Block IPs found in local             0
*blockIPBlockTablesForever*                    IP-blocklists forever 
                                               (sets RedirectDenyIPBlockTables = 1)
=================================  ==========  =====================================================  ===============================

