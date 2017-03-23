.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-block-by-abuseipdb-com:

Block by abuseipdb.com
----------------------

**Important:** Use of the abuseipdb.com API requires not only that curl is installed, it's necessary that **curl** and **nss** are installed and **both updated to the newest version**

In section blockByAbuseipdbCom, you set up options regarding blocking with the realtime database by `abuseipdb.com <https://www.abuseipdb.com/>`__

**Note:** options in the constants have not always the same name as the options in setup.

In column Property the corresponding value of the *constant* is added after the setup property if it differs.

=====================================  ==========  =====================================================  ================
**Property:**                          Data type:  Description:                                           Default:
=====================================  ==========  =====================================================  ================
abuseipdbComAPIId                      string      API-Id: 
                                                   Your abuseipdb.com API-Id, like 
                                                   wf30URT04cAPIboxft4N1gV2A09CityMCNpVuwac  
-------------------------------------  ----------  -----------------------------------------------------  ----------------
abuseipdbComAPIcheckbackDays           int+        checkback time:                                        90
                                                   Check back this number of days for an IP on 
                                                   abuseipdb.com
-------------------------------------  ----------  -----------------------------------------------------  ----------------
blockForEverRecurrence                 int+        block IP forever after recurrent blocking:             3
*blockForEverAbuseipdbComRecurrence*               If blocking arrived recurrently the IP will be 
                                                   blocked forever after this number of attempts
-------------------------------------  ----------  -----------------------------------------------------  ----------------
blockForever                           boolean     block IP forever: Block IPs found in local             0
*blockAbuseipdbComForever*                         Block IPs found in abuseipdb.com forever
=====================================  ==========  =====================================================  ================

