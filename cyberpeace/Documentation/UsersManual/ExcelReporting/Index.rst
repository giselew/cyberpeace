.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _users-manual-excel-reporting:

Excel reporting
---------------

In folder /Resources/Public/xls and Excel file is available for some reporting on the log data. Alternatively you can use the OpenOffice-file, it is different but same.

Use the SQL query in the Excel-Sheet "MySQL query" to retrieve the last 100 log entries of CyberPeace.

Copy/paste the SQL in a MySQL Query window. Copy the result (Tab-separated)

Return to Excel and select all data in sheet "Paste result area" and paste the result of your query into Excel.

In Excel menu, select "data" and "refresh all"

In sheet "blocked or allowed by day, hour" two charts show the development of IPs, blocked and allowed, at a per day and a per hour base

.. figure:: /Images/exceldays.png

In sheet "distribution" a pie chart shows the distribution among blocked and allowed IPs

.. figure:: /Images/exceldistribution.png

In sheet "last 100" we just added headings with titles for the columns of data pasted
