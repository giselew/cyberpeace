.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _configuration-block-by-crawler:

Block by crawler
----------------

In section blockByCrawler, you set up options regarding blocking of spiders, bots and crawlers.

=================================  ==========  =====================================================  ==============================================
**Property:**                      Data type:  Description:                                           Default:
=================================  ==========  =====================================================  ==============================================
CrawlerAgentStrings                string      Crawlers, user agent identification pattern:           Perl,PycURL,package,curl,AU-MIC,Python,Test,
                                               Additionally to 'googlebot','yahoo','baidu',           Wotbox,Lipperhey,Traveler,FDiag,bot,lucid,
                                               'msnbot','bingbot','spider','yandex','jeevez'          Mining,crawl,protect,Walker,Checker,DuckDuck,
                                               the following parts of the user agent                  LinkFinder,Ezooms,filterdb,findlinks,monitor,
                                               identify a crawler                                     blast,gonzo,htdig,archiver,jobs,ips-agent,
                                                                                                      larbin,linkdex,MajesticSEO,Survey,
                                                                                                      OpenLinkProfiler,eeker,picmole,Qualidator,
                                                                                                      ReverseGet,schrein,Scooter,search,SEOkicks,
                                                                                                      sistrix,thunderstone,TinEye,Unister,
                                                                                                      Webinator,Webmaster,xovi,Yeti
---------------------------------  ----------  -----------------------------------------------------  ----------------------------------------------
blockCrawlerAgentStrings           string      Strategy 'disallow crawlers':                          Baidu,SzeNam
                                               When activateBanBlockByCrawler=1 and 
                                               allowOnlyCrawlerAgentStrings is empty, then these 
                                               parts of the User Agent qualify a crawler for 
                                               blocking
---------------------------------  ----------  -----------------------------------------------------  ----------------------------------------------
allowOnlyCrawlerAgentStrings       string      Strategy 'allowed crawlers only' (recommended):        Google,Yahoo,LinkedIn,msnbot,bingbot,
                                               When activateBanBlockByCrawler=1 then these parts of   Facebook,Facebot,DuckDuck,Twitter,ia_archiver
                                               the User Agent qualify a crawler as allowed - all 
                                               other crawlers are blocked 
                                               (blockCrawlerAgentStrings has no more effect)    
---------------------------------  ----------  -----------------------------------------------------  ----------------------------------------------
blockIfNoUserAgent                 boolean     No user-agent blocking:                                1
                                               Clients hiding the user agent can be blocked here
=================================  ==========  =====================================================  ==============================================

