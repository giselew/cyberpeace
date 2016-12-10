.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-block-by-sys-log:

Block by TYP03 syslog
---------------------

In section blockBySysLog, you set up options regarding blocking bad IPs found in the TYP03-syslog

**Note:** options in the constants have not always the same name as the options in setup.

In column Property the corresponding value of the *constant* is added after the setup property if it differs.

=================================  ==========  =====================================================  ===============================
**Property:**                      Data type:  Description:                                           Default:
=================================  ==========  =====================================================  ===============================
identificationList                 string      identification patterns:                               HMAC,RealURL was not able
*syslogIdentificationList*                     If a in syslog.details a pattern of this list is 
                                               found, then the associated IP will be blocked
---------------------------------  ----------  -----------------------------------------------------  -------------------------------
recheckAfterMinutes                int+        Syslog will be checked after a minimal interval of     20 
*recheckSyslogAfterMinutes*                    this number of minutes
---------------------------------  ----------  -----------------------------------------------------  -------------------------------
blockForever                       boolean     block IP forever:                                      0
*blockSyslogForever*                           Block IPs found in syslog forever
=================================  ==========  =====================================================  ===============================

