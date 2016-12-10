<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2015 - 2016 Gisele Wendl <gisele.wendl@toctoc.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   72: class tx_cyberpeace_pi1 extends tslib_pibase
 *  121:     public function checkDataSubmission()
 *  132:     public function main($content, $conf = array())
 * 1061:     private function syslogCheckItem($conf, $confstoragePid, $syslogIP, $identstr)
 * 1173:     private function isGoodIPnoCheck($ip, $conf)
 * 1198:     private function htaccessinitialize()
 * 1235:     private function htaccessfinalize($denyarr)
 * 1250:     private function getLastURL()
 * 1279:     protected function finishblocking($conf, $ipinfo, $ip, $canUnblock, $finishblockingforabuseipdb = 0)
 * 1396:     private function checkhtaccessandblock($ip, $checkhtaccess = TRUE)
 * 1440:     private function checkIPabuse($conf, $ipaddr)
 * 1510:     private function getRequestIP()
 * 1522:     private function getBlacklistForIP($ip)
 * 1539:     private function checkTableBLs($ipaddr)
 * 1550:     private function checkLocalBL($ipaddr)
 * 1589:     private function checkStaticBL($ipaddr)
 *
 * TOTAL FUNCTIONS: 15
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

if (version_compare(TYPO3_version, '6.0', '<')) {
	require_once(PATH_tslib . 'class.tslib_pibase.php');

} else {
	require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('frontend') . 'Classes/Plugin/AbstractPlugin.php';
}

if (version_compare(TYPO3_version, '6.3', '>')) {
	(class_exists('tslib_pibase', FALSE)) ? TRUE : class_alias('TYPO3\CMS\Frontend\Plugin\AbstractPlugin', 'tslib_pibase');
	(class_exists('t3lib_div', FALSE)) ? TRUE : class_alias('TYPO3\CMS\Core\Utility\GeneralUtility', 't3lib_div');
	if ((!t3lib_extMgm::isLoaded('compatibility6')) && (!t3lib_extMgm::isLoaded('compatibility7'))) {
		(class_exists('tslib_cObj', FALSE)) ? TRUE : class_alias('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer', 'tslib_cObj');
	}
}

 /**
  * IP-based redirects to an appropriate page or access forbidden
  *
  * @access public
  * @author Gisèle Wendl <gisele.wendl@toctoc.ch>
  */
class tx_cyberpeace_pi1 extends tslib_pibase {

	/**
	* same as class name
	*
	* @access	public
	* @var		string		$prefixId
	*/
	public $prefixId = 'tx_cyberpeace_pi1';

	/**
	* Path to this script relative to the extension dir.
	*
	* @access	public
	* @var		string		$scriptRelPath
	*/
	public $scriptRelPath = 'pi1/class.tx_cyberpeace_pi1.php';

	/**
	* The extension key
	*
	* @access	public
	* @var		string		$extKey
	*/
	public $extKey = 'cyberpeace';

	/**
	* content Object
	*
	* @access   private
	* @var      object  $cObj
	*/
	public $cObj;

	public $hitipcomment = '';
	public $hitip = '';
	protected $blockIP = 0;

	protected $htaccessfile = '';
	protected $arrtoend = array();
	protected $arrtostart = array();
	protected $htaccesscontent = '';

/**
 * hook to be executed by TypoScriptFrontendController
 *
 * @param	\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController		$frontendController
 * @return	void		nothing
 */
    public function checkDataSubmission()   {
		$this->main('', array());
    }

	/**
	 * Main processing
	 *
	 * @param	string		$content: dummy content
	 * @param	array		$conf: dummy conf
	 * @return	void		nothing, can exit();
	 */
	public function main($content, $conf = array())	{
		// get $confs
		if (count($conf) < 2) {
			$conf = array();
			$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_cyberpeace_pi1.'];
		}

		$conf2 = array();
		$conf2 = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_toctoccomments_pi1.'];

		// get the sysfolder-id (storagePid) of the IP-list
		if (trim($conf['storagePid']) == '') {
			if ($conf2['storagePid'] != '') {
				$confstoragePid = $conf2['storagePid'];
			} else {
				$confstoragePid = 0;
			}

		} else {
			$confstoragePid = $conf['storagePid'];
		}

		// first check the local IP-blocktables (added manually or imported from SpamHaus

		// admin no check?
		if(t3lib_div::cmpIP(t3lib_div::getIndpEnv('REMOTE_ADDR'), $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'])) {
			if (intval($conf['blockByLocalIPBlockTables.']['noCheckDevIPMask']) == 1) {
				$conf['activateBanBlockByLocalIPBlockTables'] = 0;
			}

		}

		$interestingCrawlers = array('googlebot','yahoo','baidu','msnbot','bingbot','spider','yandex','jeevez' );
		$interestingCrawlersConf = explode(',', $conf['blockByCrawler.']['CrawlerAgentStrings']);

		$tmparr = array_merge($interestingCrawlers, $interestingCrawlersConf);
		$interestingCrawlers = array_unique($tmparr);

		usort($interestingCrawlers, function($a, $b) {
			$ret = strlen($b) - strlen($a);
			return $ret;
		});

		$blockCrawlerAgentStrings = explode(',', $conf['blockByCrawler.']['blockCrawlerAgentStrings']);
		$blockCrawlerAgentStrings = array_unique($blockCrawlerAgentStrings);

		usort($blockCrawlerAgentStrings, function($a, $b) {
			$ret = strlen($b) - strlen($a);
			return $ret;
		});

		$allowOnlyCrawlerAgentStrings = explode(',', $conf['blockByCrawler.']['allowOnlyCrawlerAgentStrings']);
		$allowOnlyCrawlerAgentStrings = array_unique($allowOnlyCrawlerAgentStrings);

		usort($allowOnlyCrawlerAgentStrings, function($a, $b) {
			$ret = strlen($b) - strlen($a);
			return $ret;
		});

		$countinterestingCrawlers = count($interestingCrawlers);
		$countblockCrawlerAgentStrings = count($blockCrawlerAgentStrings);
		$countallowOnlyCrawlerAgentStrings = count($allowOnlyCrawlerAgentStrings);
		$identstr = '';
		$blockcrawler = FALSE;
		$blockedidentstr = '';
		if ($_SERVER['HTTP_USER_AGENT']){
			if (trim($_SERVER['HTTP_USER_AGENT']) != ''){

				$SERVERHTTPUSERAGENT= $_SERVER['HTTP_USER_AGENT'];
				if ($conf['testuseragent'] != '') {
					$SERVERHTTPUSERAGENT = $conf['testuseragent'];
				}

				for ($i=0; $i < $countinterestingCrawlers; $i++){
					if (str_replace(strtolower(trim($interestingCrawlers[$i])), '', strtolower($SERVERHTTPUSERAGENT)) != strtolower($SERVERHTTPUSERAGENT)) {
						$identstr = 'Crawler identified by "' . trim($interestingCrawlers[$i]) . '"' . "\n" . $SERVERHTTPUSERAGENT . "\n";
						$blockedidentstr = 'Blocked crawler identified by "' . trim($interestingCrawlers[$i]) . '"' . "\n" . $SERVERHTTPUSERAGENT . "\n";
						break;
					}

				}

				if ($identstr != '') {
					if ($conf['activateBanBlockByCrawler'] == 1) {
						if (($countallowOnlyCrawlerAgentStrings > 0 ) || ($countblockCrawlerAgentStrings > 0 )) {
							if ($countallowOnlyCrawlerAgentStrings > 0) {
								$blockcrawler = TRUE;
							// filter positive
								for ($i=0; $i < $countallowOnlyCrawlerAgentStrings; $i++){
									if (str_replace(strtolower(trim($allowOnlyCrawlerAgentStrings[$i])), '', strtolower($SERVERHTTPUSERAGENT)) != strtolower($SERVERHTTPUSERAGENT)) {
										$blockcrawler = FALSE;
										$identstr = 'Allowed crawler identified by "' . trim($allowOnlyCrawlerAgentStrings[$i]) . '"' . "\n" . $SERVERHTTPUSERAGENT . "\n";
										break;
									}

								}

								If ($blockcrawler == TRUE) {
									$identstr = $blockedidentstr;
								}

							} elseif ($countblockCrawlerAgentStrings > 0) {
							//filter negative
								for ($i=0; $i < $countblockCrawlerAgentStrings; $i++){
									if (str_replace(strtolower(trim($blockCrawlerAgentStrings[$i])), '', strtolower($SERVERHTTPUSERAGENT)) != strtolower($SERVERHTTPUSERAGENT)) {
										$identstr = 'Blocked crawler identified by "' . trim($blockCrawlerAgentStrings[$i]) . '"' . "\n" . $SERVERHTTPUSERAGENT . "\n";
										$blockcrawler = TRUE;
										break;
									}

								}

							}

						}

					}

				}

			} else {
				$identstr = 'Empty HTTP_USER_AGENT' . "\n";
				if ($conf['blockByCrawler.']['blockIfNoUserAgent'] == 1) {
					if ($conf['activateBanBlockByCrawler']==1) {
						$blockcrawler = TRUE;
					}

				}

			}

		} else {
			$identstr = 'No HTTP_USER_AGENT' . "\n";
			if ($conf['blockByCrawler.']['blockIfNoUserAgent'] == 1) {
				if ($conf['activateBanBlockByCrawler']==1) {
						$blockcrawler = TRUE;
				}
			}

		}

		if ($blockcrawler == TRUE) {
			$ip = $this->getRequestIP();

			if ($conf['testip'] != '') {
				$ip = $conf['testip'];
			}

			$ipinfo = array(
					'uid' => 0,
					'pid' => $confstoragePid,
					'ipaddr' => $ip,
					'blockfe' => 1,
					'blockhtaccess' => 1,
					'deleted' => 0,
					'blockforever' => 0,
					'crdate' => time()-5,
					'tstamp' => time()-5,
					'tstampunblock' => time()-5,
					'recurrence' => 0,
					'comment' => $identstr,
					'accesscount' => 0,
					'lasturl' => '',
					'isocode' => '',
					'category' => '',
					'country' => '',
					'abuseiplasttstamp' => 0,
					'blockingorigin'  => '',
			);

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,pid,ipaddr,deleted,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock,recurrence,comment,
						accesscount,lasturl,isocode,category,country,abuseiplasttstamp',
					'tx_cyberpeace_iplist', 'ipaddr="'.$ip.'"', '', '');

			$num_rows = count($res);

			if ($num_rows>0) {
				if ($res[0]['comment'] != '') {
					if ($identstr != '') {
						if ($identstr != $res[0]['comment']) {
							if (str_replace($identstr, '', $res[0]['comment']) == $res[0]['comment']) {
								$identstr = $identstr . "\n" . $res[0]['comment'];
							} else {
								$identstr = $res[0]['comment'];
							}
							
						}
						
					} else {
						$identstr = $res[0]['comment'];
					}
					
				}

				$ipinfo = array(
						'uid' => $res[0]['uid'],
						'pid' => $res[0]['pid'],
						'ipaddr' => $ip,
						'blockfe' => $res[0]['blockfe'],
						'blockhtaccess' => $res[0]['blockhtaccess'],
						'deleted' => 0,
						'blockforever' => $res[0]['blockforever'],
						'crdate' => $res[0]['crdate'],
						'tstamp' => $res[0]['tstamp'],
						'tstampunblock' => $res[0]['tstampunblock'],
						'recurrence' => $res[0]['recurrence'],
						'comment' => $identstr,
						'accesscount' => $res[0]['accesscount'],
						'lasturl' => $res[0]['lasturl'],
						'isocode' => $res[0]['isocode'],
						'category' => $res[0]['category'],
						'country' => $res[0]['country'],
						'abuseiplasttstamp' => $res[0]['abuseiplasttstamp'],
						
						'blockingorigin'  => $res[0]['blockingorigin'],
				);

			}

			$ipinfo['accesscount']++;
			$ipinfo['lasturl'] = $this->getLastURL();
			$ipinfo['blockingorigin'] = 'crawler blocklist';
			$ipinfo['blockfe'] = 1;
			$ipinfo['blockhtaccess'] = 1;

			$this->finishblocking($conf, $ipinfo, $ip, FALSE);
			$this->blockIP = 0;
			// If not present AND comment!="" add it
			if ($ipinfo['blockhtaccess'] == 1) {
				$this->checkhtaccessandblock($ip, FALSE);

			}

		}

		if (intval($conf['activateBanBlockByLocalIPBlockTables']) == 1) {
			$redirect = FALSE;
			$ip = $this->getRequestIP();

			if ($conf['testip'] != '') {
				$ip = $conf['testip'];
			}

			if ($this->isGoodIPnoCheck($ip, $conf) == FALSE) {
				$Blacklist = array();
				$Blacklist = $this->getBlacklistForIP($ip);
				$redirectfromlocalblocklist = FALSE;

				if ($Blacklist[0] == TRUE) {
					$redirect = TRUE;
					$redirectfromlocalblocklist = TRUE;
					$redirectPage = $conf['blockByLocalIPBlockTables.']['RedirectPageLocal'];
				} elseif ($Blacklist[1] == TRUE) {
					$redirect = TRUE;
		 			$redirectPage = $conf['blockByLocalIPBlockTables.']['RedirectPageStatic'];
				}

				$blockingoriginmessage = '';
				$protocol = '';
				$comment = '';
				$blockingorigin = '';
				$RedirectDeny = 0;
				$syslogcheckneeded = TRUE;
				$needsupdate = FALSE;
				if ($redirect == TRUE) {
					// no other checks needed
					$conf['activateBanBlockByAbuseipdbCom'] = 0;
					$conf['activateBanBlockBySysLog'] = 0;
					$syslogcheckneeded = FALSE;
					$RedirectDeny = $conf['blockByLocalIPBlockTables.']['RedirectDeny'];

					$blockingorigin = 'imported IP blocklist';
					$blockingoriginmessage = 'your IP is in a public IP blocklist';
					if ($redirectfromlocalblocklist == TRUE) {
						$blockingorigin = 'local IP blocklist';
						$blockingoriginmessage = 'your IP is in a private IP blocklist';
					}

					$comment = 'IP ' . $ip . ' found in ' . $blockingorigin . "\n" . 'Info from IP blocklist: IP or IP-range is ' . trim($this->hitip . ' ' . $this->hitipcomment) .
							"\n" . 'Useragent: ' . $_SERVER['HTTP_USER_AGENT'];

					if ($identstr != '') {
						$identstr = $identstr . "\n";
					}

				}

				$syslogIP = $ip;

				$ipinfo = array(
						'uid' => 0,
						'pid' => $confstoragePid,
						'ipaddr' => $syslogIP,
						'blockfe' => intval($redirect),
						'blockhtaccess' => $RedirectDeny,
						'deleted' => 0,
						'blockforever' => 0,
						'crdate' => time()-3,
						'tstamp' => time()-3,
						'tstampunblock' => time()-3,
						'recurrence' => 0,
						'comment' => $identstr . $comment,
						'accesscount' => 0,
						'lasturl' => '',
						'isocode' => '',
						'category' => '',
						'country' => '',
						'abuseiplasttstamp' => 0,						
						'blockingorigin' => $blockingorigin,
						);

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,pid,ipaddr,deleted,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock,recurrence,comment,
								accesscount,lasturl,isocode,category,country,abuseiplasttstamp',
						'tx_cyberpeace_iplist', 'ipaddr="'.$syslogIP.'"', '', '');

				$num_rows = count($res);

				if ($num_rows > 0) {
					if ($res[0]['blockingorigin'] != '') {
						$syslogcheckneeded = FALSE;
					}

					if ($res[0]['comment'] != '') {
						if ($identstr != '') {
							if (str_replace('abuseipdb', '', $res[0]['comment']) == $res[0]['comment']) {
								if (str_replace($identstr, '', $res[0]['comment']) == $res[0]['comment']) {
									$identstr = $identstr . "\n" . $res[0]['comment'];
								} else {
									$identstr = $res[0]['comment'];
								}
								
							}
							
						} else {
							if (str_replace('abuseipdb', '', $res[0]['comment']) == $res[0]['comment']) {
								$identstr = $res[0]['comment'];
							}
							
						}
						
					}
					
					$newcomment = '';
					
					if (str_replace($comment, '', $res[0]['comment']) == $res[0]['comment']){
						$newcomment = $comment;
					}

					if ($identstr != '') {
						if (str_replace($identstr, '', $res[0]['comment']) == $res[0]['comment']) {
							$newcomment = trim($identstr) . "\n" . $comment;
						}
												
					}
					
					if ($newcomment != '') {
						$newcomment = trim($newcomment) . "\n" . $res[0]['comment'];
					} else {
						$newcomment = $res[0]['comment'];
					}
										
					if ($redirect == FALSE) {
						$blockingorigin=$res[0]['blockingorigin'];
					}
					
					$ipinfo = array(
							'uid' => $res[0]['uid'],
							'pid' => $res[0]['pid'],
							'ipaddr' => $syslogIP,
							'blockfe' => $res[0]['blockfe'],
							'blockhtaccess' => $res[0]['blockhtaccess'],
							'deleted' => 0,
							'blockforever' => $res[0]['blockforever'],
							'crdate' => $res[0]['crdate'],
							'tstamp' => $res[0]['tstamp'],
							'tstampunblock' => $res[0]['tstampunblock'],
							'recurrence' => $res[0]['recurrence'],
							'comment' => $newcomment,
							'accesscount' => $res[0]['accesscount'],
							'lasturl' => $res[0]['lasturl'],
							'isocode' => $res[0]['isocode'],
							'category' => $res[0]['category'],
							'country' => $res[0]['country'],
							'abuseiplasttstamp' => $res[0]['abuseiplasttstamp'],							
							'blockingorigin'  => $blockingorigin,
							);

					if (($ipinfo['blockhtaccess']+$ipinfo['blockforever']) > 0) {
						$this->checkhtaccessandblock($syslogIP, TRUE);
					}

				} else {
					$needsupdate = TRUE;
				}

				$docheckAbuseipdb = FALSE;
				$canUnblock = FALSE;
				$canUnblockFe = FALSE;
				$ipinfo['accesscount'] = $ipinfo['accesscount']+1;

				if ($redirect == FALSE) {
					if ($ipinfo['blockhtaccess'] > 0) {
						if (($ipinfo['tstamp'] + ($conf['unblockAfterDays']*3600*24)) <= time()) {
							// recheck period over, needs check
							$ipinfo['deleted'] = 0;
							$canUnblock = TRUE;
							$needsupdate = TRUE;
						}

					}

					if (($ipinfo['blockfe'] > 0) && ($ipinfo['blockhtaccess'] == 0)) {
						if (($ipinfo['tstamp'] + ($conf['unblockAfterDays']*3600*24)) <= time()) {
							// recheck period over, needs check
							$ipinfo['deleted'] = 0;
							$canUnblockFe = TRUE;
							$needsupdate = TRUE;
						}

					}

				}

				$ipinfo['lasturl'] = $this->getLastURL();

				if ($redirect == TRUE) {
					// listed
					$ipinfo['blockfe'] = 1;
					$ipinfo['blockhtaccess'] = (intval($conf['blockByLocalIPBlockTables.']['blockForever'])+$RedirectDeny) > 0 ? 1 : 0;
					$ipinfo['blockforever'] = intval($conf['blockByLocalIPBlockTables.']['blockForever']);
					$needsupdate = TRUE;
				} else {
					if ($ipinfo['blockfe'] == 1) {
						$needsupdate = TRUE;
					}
					
					if ($ipinfo['blockforever'] == 0) {
						$ipinfo['blockfe'] = 0;
						$ipinfo['blockhtaccess'] = 0;
					}
									
				}

				if (($ipinfo['blockfe'] == 0) && ($ipinfo['blockhtaccess'] == 0)) {
					// IP gets trough, but could be bocked by abuseipdb.com
					if (($ipinfo['tstamp'] + ($conf['recheckGoodIPAfterHours']*3600)) <= time()) {
						$docheckAbuseipdb = TRUE;
					}
					
				}

				if ($GLOBALS['TSFE']->id != $redirectPage) {
					if ($redirect == TRUE) {
						$protocol = 'BL: ' . strftime('%Y/%m/%d %H:%M:%S', microtime(TRUE)) . ': ' . trim($this->hitip . ' ' . $this->hitipcomment . ' ' .
									$_SERVER['HTTP_USER_AGENT']) . ' idfd "' . $ip . '"@@' . $GLOBALS['TSFE']->id . '@@' . $GLOBALS['TSFE']->lang;

						// EXT:toctoc_comments protocol of access attemps by blocked IPs or IP-ranges is updated here
						if ($conf2['advanced.']['protocolBlacklistedIPs'] == 1) {
							$blprt = str_replace('cyberpeace', 'toctoc_comments', realpath(dirname(__FILE__)) . '/blacklistprotocol.txt');
							if (!(file_exists($blprt))) {
								if (version_compare(TYPO3_version, '6.0', '<')) {
									t3lib_div::writeFile($blprt, $protocol);
								} else	{
									\TYPO3\CMS\Core\Utility\GeneralUtility::writeFile($blprt, $protocol);
								}

							} else {
								$content = file_get_contents($blprt);
								$contentarr = explode("\r\n", $content);
								$testelem= 	$contentarr[count($contentarr)-1];
								$testelemarr = explode('@@', $testelem);
								$testelemarrhua = explode(' idfd "', $testelemarr[0]);
								$testelemarrhua2 = explode('"', $testelemarrhua[1]);
								$hua = $testelemarrhua2[0];
								$protocol = str_replace("\r\n", ', ', $protocol);
								$protocol = str_replace("\n", ', ', $protocol);
								if (($hua != $ip) || ($testelemarr[1] != $GLOBALS['TSFE']->id) || ($testelemarr[2] != $GLOBALS['TSFE']->lang)) {
									$content = $content . "\r\n" . $protocol;
									$contentarr = explode("\r\n", $content);
									if (count($contentarr) > $conf2['advanced.']['protocolBlacklistedIPsMaxLines']) {
										array_shift($contentarr);
										$content = implode("\r\n", $contentarr);
									}

									// Write the contents back to the file
									file_put_contents($blprt, $content);
								}

							}

						}

					}

					if ($needsupdate == TRUE) {
						$this->finishblocking($conf, $ipinfo, $syslogIP, $canUnblock);
					} else {
						if ($ipinfo['blockhtaccess'] == 1) {
							$this->checkhtaccessandblock($syslogIP, TRUE);
						}

					}

					if ($this->blockIP == 3) {
						//echo 'exit 3'; exit;
						$this->checkhtaccessandblock($syslogIP, FALSE);
					} else {

						if ($redirect == TRUE) {
							// ...and not yet redirected in finishblocking, then redirect the client now:
							// if redirect page is int, then it's an internal page
							if (intval($redirectPage) > 0) {
								$this->cObj = t3lib_div::makeInstance('tslib_cObj');

								$params	= array(
										'parameter' => $redirectPage,
								);

								$conflink = array(
										'useCacheHash' => FALSE,
										// Link to current page
										'parameter' => $redirectPage,
										// Set additional parameters
										'additionalParams' => '',
										// We must add cHash because we use parameters ... hmmm - not that sure!
										// We want link only
										'returnLast' => 'url',
										'ATagParams' => '',
										'forceAbsoluteUrl' => 1,
								);

								$commentsPageIdPage = $this->cObj->typoLink('', $conflink);
								header('Location: ' . $commentsPageIdPage);
								exit;
							} else {
								header('Location: ' . $redirectPage);
								exit;
							}

						}

					}

				}

			}

		}

		// check abuseipdb.com

		// admin no check?
		if(t3lib_div::cmpIP(t3lib_div::getIndpEnv('REMOTE_ADDR'), $GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'])) {
			if (intval($conf['noCheckDevIPMask']) == 1) {
				$conf['activateBanBlockByAbuseipdbCom'] = 0;
			}
			
		}

		if (intval($conf['activateBanBlockByAbuseipdbCom']) == 1) {
			// check $conf['blockByAbuseipdbCom.']['abuseipdbComAPIId']
			if (trim($conf['blockByAbuseipdbCom.']['abuseipdbComAPIId']) != '') {
				$ip = $this->getRequestIP();

				if ($conf['testip'] != '') {
					$ip = $conf['testip'];
				}

				$ipinfo = array(
						'uid' => 0,
						'pid' => $confstoragePid,
						'ipaddr' => $ip,
						'blockfe' => 0,
						'blockhtaccess' => 0,
						'deleted' => 0,
						'blockforever' => 0,
						'crdate' => time()-2,
						'tstamp' => time()-2,
						'tstampunblock' => time()-2,
						'recurrence' => 0,
						'comment' => '',
						'accesscount' => 0,
						'lasturl' => '',
						'isocode' => '',
						'category' => '',
						'country' => '',
						'abuseiplasttstamp' => 0,
						
						'blockingorigin'  => '',
				);

				$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,pid,ipaddr,deleted,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock,recurrence,comment,
						accesscount,lasturl,isocode,category,country,abuseiplasttstamp',
						'tx_cyberpeace_iplist', 'ipaddr="'.$ip.'"', '', '');

				$num_rows = count($res);

				if ($num_rows>0) {
					$ipinfo = array(
							'uid' => $res[0]['uid'],
							'pid' => $res[0]['pid'],
							'ipaddr' => $ip,
							'blockfe' => $res[0]['blockfe'],
							'blockhtaccess' => $res[0]['blockhtaccess'],
							'deleted' => 0,
							'blockforever' => $res[0]['blockforever'],
							'crdate' => $res[0]['crdate'],
							'tstamp' => $res[0]['tstamp'],
							'tstampunblock' => $res[0]['tstampunblock'],
							'recurrence' => $res[0]['recurrence'],
							'comment' => $res[0]['comment'],
							'accesscount' => $res[0]['accesscount'],
							'lasturl' => $res[0]['lasturl'],
							'isocode' => $res[0]['isocode'],
							'category' => $res[0]['category'],
							'country' => $res[0]['country'],
							'abuseiplasttstamp' => $res[0]['abuseiplasttstamp'],
							
							'blockingorigin'  => $res[0]['blockingorigin'],
					);

					if (($ipinfo['blockhtaccess']+$ipinfo['blockforever']) > 0) {
						$this->checkhtaccessandblock($ip, TRUE);
					}

				}

				$needsCheck = FALSE;
				$canUnblock = FALSE;

				$ipinfo['accesscount']++;
				$ipinfo['lasturl'] = $this->getLastURL();

				if ($ipinfo['uid'] == 0) {
					// newbee, needs check
					$needsCheck = TRUE;
				}

				if (($ipinfo['blockhtaccess']+$ipinfo['blockforever']) == 0) {
					if ((($ipinfo['tstamp'] + ($conf['recheckGoodIPAfterHours']*3600)) <= time()) || ($docheckAbuseipdb == TRUE)) {
						// recheck period over, needs check
						$needsCheck = TRUE;
						$ipinfo['deleted'] = 0;
						$canUnblock = TRUE;
					}

				}

				if (($ipinfo['blockfe']+$ipinfo['blockhtaccess']) > 0) {
					if (($ipinfo['tstamp'] + ($conf['unblockAfterDays']*3600*24)) <= time()) {
						// recheck period over, needs check
						$needsCheck = TRUE;
						$ipinfo['deleted'] = 0;
						$canUnblock = TRUE;
					}

				}

				if ($needsCheck == TRUE) {
					// check ipabuse for IP
					$commentarr = $this->checkIPabuse($conf, $ip);
					/*ip,
					 country Poland,
					isoCode PL,
					category [15] (array),
					created: Wed, 19 Oct 2016 18:44:07 +0000*/

					if (count($commentarr) == 0) {
						// not listed
						$ipinfo['comment'] = $identstr;
						if ($ipinfo['blockforever'] == 0) {
							//$ipinfo['blockfe'] = 0;
							$ipinfo['blockhtaccess'] = 0;
							$ipinfo['blockingorigin']  = '';
						}

					} else {
						// listed
						if (array_key_exists('created', $commentarr)) {
							$strlastentry = $commentarr['created'];
							$timesin  = '1';
							$ipinfoisocode = $commentarr['isoCode'];
							$ipinfocountry = $commentarr['country'];
							if (is_array($commentarr['category'])) {
								$ipinfocategory = implode(', ', $commentarr['category']);
							} else {
								$ipinfocategory = trim($commentarr['category']);
							}

							$ipinfoabuseiplasttstamp = time($commentarr['created']);
						} else {
							$strlastentry = $commentarr[0]['created'];
							$timesin  = count($commentarr);
							$ipinfoisocode = $commentarr[0]['isoCode'];
							$ipinfocountry = $commentarr[0]['country'];
							if (is_array($commentarr[0]['category'])) {
								$ipinfocategory = implode(', ', $commentarr[0]['category']);
							} else {
								$ipinfocategory = trim($commentarr[0]['category']);
							}

							$ipinfoabuseiplasttstamp = time($commentarr[0]['created']);
						}

						$comment = $identstr . 'IP ' . $ip . ' found ' . $timesin . ' time(s) in abuseipdb.com' . "\n" . 'last entry: ' .
								$strlastentry;
						$ipinfo['blockingorigin'] = 'abuseipdb.com';
						$ipinfo['comment'] = $comment;
						$ipinfo['isocode'] = $ipinfoisocode;
						$ipinfo['country'] = $ipinfocountry;
						$ipinfo['category'] = $ipinfocategory;
						$ipinfo['abuseiplasttstamp'] = $ipinfoabuseiplasttstamp;
						$ipinfo['blockfe'] = 1;
						$ipinfo['blockhtaccess'] = 1;
						$ipinfo['blockforever'] = intval($conf['blockByAbuseipdbCom.']['blockForever']);
						$canUnblock = FALSE;
					}

					$this->finishblocking ($conf, $ipinfo, $ip, $canUnblock, 1);
					if ($this->blockIP == 3) {
						// If not present AND comment!="" add it
						if ($ipinfo['blockhtaccess'] == 1) {
							$this->checkhtaccessandblock($ip, FALSE);
						}

					}

				}

			}

		}

		if (intval($conf['activateBanBlockBySysLog']) == 1) {
			$dynopttstamp = 0;
			$sqlopt = 'SELECT dynopt, dynopttstamp
						FROM tx_cyberpeace_dynopt
						WHERE dynopt = "syslog last run"';
			$resultoptmerged= $GLOBALS['TYPO3_DB']->sql_query($sqlopt);
			while ($rowsmerged = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resultoptmerged)) {
				$dynopttstamp = intval($rowsmerged['dynopttstamp']);
			}

			if ($dynopttstamp == 0) {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_cyberpeace_dynopt', array(
					'dynopt' => 'syslog last run',
				));
			}

			$rechecktstamp = time() - intval($conf['blockBySysLog.']['recheckAfterMinutes'])*60;

			if ($dynopttstamp < $rechecktstamp) {

				if ($conf['blockBySysLog.']['identificationList'] != '') {
					$ArridentificationList = explode(',', $conf['blockBySysLog.']['identificationList']);
					$cntIdents = count($ArridentificationList);

					if ($cntIdents > 0) {
						$sqlsyslog = '';
						for ($i = 0; $i < $cntIdents;$i++) {
							$sqlsyslog .= 'SELECT IP, MAX(uid) as maxuid, COUNT(uid) as nbrattempts , MAX(tstamp) as tstamlastattempt, details, \'' . trim($ArridentificationList[$i]) . '\' AS identification
							FROM sys_log
							WHERE sys_log.error IN (1,2) AND tstamp > ' . intval($dynopttstamp) . ' AND details LIKE
							\'%' . trim($ArridentificationList[$i]) . '%\'  GROUP BY details, IP';
							if ($i == ($cntIdents-1)) {
								$sqlsyslog .= '
										order by IP, maxuid DESC';
							} else {
								$sqlsyslog .= '
										UNION
										';
							}

						}

						$resultmerged= $GLOBALS['TYPO3_DB']->sql_query($sqlsyslog);
						$syslogIP='';
						$arrInsert = array();
						while ($rowsmerged = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resultmerged)) {
							if ($syslogIP != $rowsmerged['IP']) {
								if ($syslogIP != '') {
									
									$this->syslogCheckItem ($conf, $confstoragePid, $syslogIP, $tstamlastattempt, $firstsysdetails, $totalattempts);
								}

								$identification = $rowsmerged['identification'];
								$syslogIP = $rowsmerged['IP'];
								$tstamlastattempt = $rowsmerged['tstamlastattempt'];
								$firstsysdetails = $rowsmerged['details'];
								$totalattempts = intval($rowsmerged['nbrattempts']);
							} else {
								$totalattempts += intval($rowsmerged['nbrattempts']);
							}

						}

						if ($syslogIP != '') {
							$this->syslogCheckItem($conf, $confstoragePid, $syslogIP, $tstamlastattempt, $firstsysdetails, $totalattempts);
						}

					}

				}

				$GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_cyberpeace_dynopt SET ' .
						' dynopttstamp=' . time() .
						' WHERE dynopt = "syslog last run"');
				}

			}

			/// Maintain .htaccess
			$dynopttstamp = 0;
			$sqlopt = 'SELECT dynopt, dynopttstamp
						FROM tx_cyberpeace_dynopt
						WHERE dynopt = "htaccess last run"';
			$resultoptmerged= $GLOBALS['TYPO3_DB']->sql_query($sqlopt);
			while ($rowsmerged = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resultoptmerged)) {
				$dynopttstamp = intval($rowsmerged['dynopttstamp']);
				break;
			}

			if ($dynopttstamp == 0) {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_cyberpeace_dynopt', array(
					'dynopt' => 'htaccess last run',
				));
			}

			$rechecktstamp = time() - intval($conf['recheckHtaccessForUnblockAfterMinutes'])*60;

			$unblocknewercond = 'tstampunblock > ' . $dynopttstamp . ' AND ';
			If ($conf['recheckHtaccessUnblockAll'] == 1) {
				$unblocknewercond = '';
				$dynopttstamp = 0;
			}

			if ($dynopttstamp < $rechecktstamp) {
				$sqlopt = 'SELECT ipaddr, tstampunblock
						FROM tx_cyberpeace_iplist
						WHERE blockforever = 0 AND ' . $unblocknewercond . ' tstampunblock < ' . time();
				$resultoptmerged= $GLOBALS['TYPO3_DB']->sql_query($sqlopt);
				$iparr = array();
				$i = 0;

				$ipin = '("';
				while ($rowsmerged = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resultoptmerged)) {
					$iparr[$i] = $rowsmerged['ipaddr'];
					$ipin .= $rowsmerged['ipaddr'] . '", "';
					$i++;
				}

				$ipin .= '0.0")';

				$denyarr = $this->htaccessinitialize();
				// get .htaccess
				if (is_array($denyarr)) {

					$cntdenys = count($denyarr);
					$foundhtIP = FALSE;
					$cntips = count($iparr);
					// Find IP in List from DB
					for ($j=0;$j<$cntips;$j++) {
						// Find IP in .htaccess and remove it
						for ($i=0; $i < $cntdenys; $i++) {
							if (trim($denyarr[$i]) == $iparr[$j]) {
								unset($denyarr[$i]);
								break;
							}
						}

					}

					$this->htaccessfinalize($denyarr);

				}

				$upd = $GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_cyberpeace_iplist SET blockhtaccess=0 WHERE ipaddr IN '. $ipin .'');

		// deleteUnusedGoodIPsAfterDays
				if (intval($conf['deleteUnusedGoodIPsAfterDays']) > 0) {
					$checkdeltstamp = time() - intval($conf['deleteUnusedGoodIPsAfterDays'])*60*60*24;
				} else {
					$checkdeltstamp = time() - 7*60*60*24;
				}

				$GLOBALS['TYPO3_DB']->sql_query('DELETE FROM tx_cyberpeace_iplist WHERE blockfe = 0 AND blockhtaccess = 0 AND blockforever = 0 AND tstamp < ' . $checkdeltstamp . '');

		// deleteUnusedUnblockedIPsAfterDays
				if (intval($conf['deleteUnusedUnblockedIPsAfterDays']) > 0) {
					$checkdeltstamp = time() - intval($conf['deleteUnusedUnblockedIPsAfterDays'])*60*60*24;
				} else {
					$checkdeltstamp = time() - 14*60*60*24;
				}

				$GLOBALS['TYPO3_DB']->sql_query('DELETE FROM tx_cyberpeace_iplist WHERE blockingorigin != \'\' AND blockhtaccess = 0 AND
						blockforever = 0 AND tstampunblock != 0 AND tstampunblock < ' . $checkdeltstamp . '');
		// deleteDeleted
				$GLOBALS['TYPO3_DB']->sql_query('DELETE FROM tx_cyberpeace_iplist WHERE deleted = 1');
				
		// Log
				if (intval($conf['writeLogOnrecheckhtaccess']) == 1) {
					$sqllog = 'SELECT COUNT(*) AS totalips,
	 SUM(CASE WHEN (blockfe+blockhtaccess+blockforever)=0 THEN 1 ELSE 0 END) AS fullallowedips,
	SUM(CASE WHEN (((blockfe+blockhtaccess+blockforever)=0) AND (comment LIKE "%crawler%")) THEN 1 ELSE 0 END) AS allowedcrawlers,
	SUM(CASE WHEN (((blockfe = 1) AND (blockhtaccess+blockforever)=0) AND (blockingorigin != "")) THEN 1 ELSE 0 END) AS unblockedwaitforrecheck,
	SUM(CASE WHEN (((blockhtaccess+blockforever)>0) AND (blockingorigin = "crawler blocklist")) THEN 1 ELSE 0 END) AS blockedcrawlers,
	SUM(CASE WHEN (((blockhtaccess+blockforever)>0) AND (blockingorigin = "abuseipdb.com")) THEN 1 ELSE 0 END) AS blockedbyabuseipdb,
	SUM(CASE WHEN (((blockhtaccess+blockforever)>0) AND (blockingorigin = "local IP blocklist")) THEN 1 ELSE 0 END) AS blockedbylocalbl,
	SUM(CASE WHEN (((blockhtaccess+blockforever)>0) AND (blockingorigin = "syslog")) THEN 1 ELSE 0 END) AS blockedbysyslog,
	SUM(CASE WHEN (((blockhtaccess+blockforever)>0) AND (blockingorigin != "")) THEN 1 ELSE 0 END) AS totalblocked,
	SUM(CASE WHEN (((blockhtaccess+blockforever)>0) AND (blockingorigin = "imported IP blocklist")) THEN 1 ELSE 0 END) AS blockedbyimportedbl
	 FROM tx_cyberpeace_iplist';
					$resultlog = $GLOBALS['TYPO3_DB']->sql_query($sqllog);
					while ($rowslog = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resultlog)) {
						$dynopttstamp = intval($rowsmerged['dynopttstamp']);
						//INSERT
						$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_cyberpeace_log', array(
								'pid' => $confstoragePid,
								'tstamp' => time(),
								'crdate' => time(),
								'totalips' => intval($rowslog['totalips']),
								'allowedips' => intval($rowslog['fullallowedips']),
								'allowedcrawlers' => intval($rowslog['allowedcrawlers']),
								'unblockedwaitforrecheck' => intval($rowslog['unblockedwaitforrecheck']),
								'blockedcrawlers' => intval($rowslog['blockedcrawlers']),
								'blockedbyabuseipdb' => intval($rowslog['blockedbyabuseipdb']),
								'blockedbylocalbl' => intval($rowslog['blockedbylocalbl']),
								'blockedbyimportedbl' => intval($rowslog['blockedbyimportedbl']),
								'blockedbysyslog' => intval($rowslog['blockedbysyslog']),
								'counthtaccess' => intval($rowslog['totalblocked']),
								'logcomment' => '',

						));
						break;
					}

					if (intval($conf['deleteOldLogAfterDays']) > 0) {
						$checkdeltstamp = time() - intval($conf['deleteOldLogAfterDays'])*60*60*24;
					} else {
						// 7 days if not > 0
						$checkdeltstamp = time() - 7*60*60*24;
					}

					$GLOBALS['TYPO3_DB']->sql_query('DELETE FROM tx_cyberpeace_log WHERE crdate < ' . $checkdeltstamp . '');

				}

				$GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_cyberpeace_dynopt SET ' .
						' dynopttstamp=' . time() .
						' WHERE dynopt = "htaccess last run"');

			}

	}

	/**
	 * [Describe function...]
	 *
	 * @param	[type]		$conf: ...
	 * @param	[type]		$confstoragePid: ...
	 * @param	[type]		$syslogIP: ...
	 * @param	[type]		$identstr: ...
	 * @return	[type]		...
	 */
	private function syslogCheckItem($conf, $confstoragePid, $syslogIP, $tstamlastattempt, $firstsysdetails, $totalattempts) {
		
		$ipinfo = array(
				'uid' => 0,
				'pid' => $confstoragePid,
				'ipaddr' => $syslogIP,
				'blockfe' => 1,
				'blockhtaccess' => 1,
				'deleted' => 0,
				'blockforever' => intval($conf['blockBySysLog.']['blockForever']),
				'crdate' => time(),
				'tstamp' => time(),
				'tstampunblock' => time(),
				'recurrence' => 0,
				'comment' => $identstr,
				'accesscount' => 0,
				'lasturl' => '',
				'isocode' => '',
				'category' => '',
				'country' => '',
				'abuseiplasttstamp' => 0,				
				'blockingorigin'  => 'syslog'
		);

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,pid,ipaddr,deleted,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock,recurrence,comment,
							accesscount,lasturl,isocode,category,country,abuseiplasttstamp',
				'tx_cyberpeace_iplist', 'ipaddr="'.$syslogIP.'"', '', '');

		$num_rows = count($res);

		$syslogcheckneeded = TRUE;
		if ($num_rows>0) {
			if ($res[0]['blockingorigin'] == 'abuseipdb.com') {
				$syslogcheckneeded = FALSE;
			}

			$ipinfo = array(
					'uid' => $res[0]['uid'],
					'pid' => $res[0]['pid'],
					'ipaddr' => $syslogIP,
					'blockfe' => $res[0]['blockfe'],
					'blockhtaccess' => $res[0]['blockhtaccess'],
					'deleted' => 0,
					'blockforever' => intval($conf['blockBySysLog.']['blockForever']),
					'crdate' => $res[0]['crdate'],
					'tstamp' => $res[0]['tstamp'],
					'tstampunblock' => $res[0]['tstampunblock'],
					'recurrence' => $res[0]['recurrence'],
					'comment' => $res[0]['comment'],
					'accesscount' => $res[0]['accesscount'],
					'lasturl' => $res[0]['lasturl'],
					'isocode' => $res[0]['isocode'],
					'category' => $res[0]['category'],
					'country' => $res[0]['country'],
					'abuseiplasttstamp' => $res[0]['abuseiplasttstamp'],					
					'blockingorigin' => 'syslog',
			);

		}

		$canUnblock = FALSE;
		$ipinfo['accesscount'] = $ipinfo['accesscount']+$totalattempts;

		if ($ipinfo['uid'] == 0) {
			// newbee, needs check
			$syslogcheckneeded = TRUE;
		}

		if (($ipinfo['blockfe']+$ipinfo['blockhtaccess']) > 0) {
			if (($ipinfo['tstamp'] + ($conf['unblockAfterDays']*3600*24)) <= time()) {
				// recheck period over, needs check
				$ipinfo['deleted'] = 0;
				$canUnblock = TRUE;
			}

		}

		// listed
		$firstsysdetailsURLarr = explode('Requested URL: ', $firstsysdetails);
		$ipinfo['lasturl'] = '';
		if (count($firstsysdetailsURLarr) == 2) {
			$ipinfo['lasturl'] = $firstsysdetailsURLarr[1];
		}

		$comment = $identstr . 'IP ' . $syslogIP . ' found ' . $totalattempts . ' time(s) in syslog' . "\n" . 'last entry: ' .
				strftime('%d.%m.%Y', $tstamlastattempt). "\n" . 'last logentry: ' . $firstsysdetails;

		$ipinfo['comment'] = $comment;
		$ipinfo['isocode'] = '';
		$ipinfo['country'] = '';
		$ipinfo['category'] = '';
		$ipinfo['abuseiplasttstamp'] = $tstamlastattempt;
		$ipinfo['blockfe'] = 1;
		$ipinfo['blockhtaccess'] = 1;

		if ($syslogcheckneeded== TRUE) {
			$this->finishblocking ($conf, $ipinfo, $syslogIP, $canUnblock);
		}

	}

	/**
	 * Checks if an IP is already marked as good and no further checks are needed
	 *
	 * @param	string		$ip: ...
	 * @param	array		$conf: ...
	 * @return	boolean		TRUE if "good IP"
	 */
	private function isGoodIPnoCheck($ip, $conf) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid,pid,ipaddr,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock',
				'tx_cyberpeace_iplist', 'ipaddr="'.$ip.'"', '', '');

		$num_rows = count($res);
		$ret = FALSE;

		if ($num_rows>0) {
			if (($ipinfo['blockfe']+$ipinfo['blockhtaccess']+$ipinfo['blockforever']) == 0) {
				if (($ipinfo['tstamp'] + ($conf['recheckGoodIPAfterHours']*3600)) > time()) {
					$ret = TRUE;
				}

			}

		}

		return $ret;
	}

	/**
	 * Initializes the processing of .htaccess
	 *
	 * @return	array		list of blocked IPs in .htaccess
	 */
	private function htaccessinitialize() {
		// get .htaccess
		$this->htaccessfile = str_replace('typo3conf' . DIRECTORY_SEPARATOR .
				'ext'  . DIRECTORY_SEPARATOR . 'cyberpeace'  . DIRECTORY_SEPARATOR . 'pi1', '', realpath(dirname(__FILE__))) . '.htaccess';
		$denyarr = 'no .htaccess';
		if (file_exists($this->htaccessfile)) {
			$denyarr = array();
			// get contents
			$this->htaccesscontent = file_get_contents($this->htaccessfile);
			// get line ##cyberpeace START
			if (str_replace('##cyberpeace START', '', $this->htaccesscontent) == $this->htaccesscontent) {
				// Insert at end if not exists
				// get line ##cyberpeace END
				// Insert at end if not exists
				$this->htaccesscontent = $this->htaccesscontent . "\n" . '##cyberpeace START';
				$this->htaccesscontent = str_replace('##cyberpeace END', '', $this->htaccesscontent);
				$this->htaccesscontent = $this->htaccesscontent . "\n" . '##cyberpeace END';
			}

			// get lines between ##cyberpeace START and END
			$this->arrtostart = explode("\n" . '##cyberpeace START', $this->htaccesscontent);
			$this->arrtoend = explode("\n" . '##cyberpeace END', $this->arrtostart[1]);

			// explode by '\ndeny from '
			$denyarr = explode("\n" . 'deny from ', $this->arrtoend[0]);

		}

		return $denyarr;
	}

	/**
	 * finalizes the processing of .htaccess
	 *
	 * @param	array		$denyarr: list of blocked IPs in .htaccess
	 * @return	string		''
	 */
	private function htaccessfinalize($denyarr) {
		// finalize
		$this->arrtoend[0] = implode("\n" . 'deny from ', $denyarr);
		$this->arrtostart[1] = implode("\n" . '##cyberpeace END', $this->arrtoend);
		$this->htaccesscontent = implode("\n" . '##cyberpeace START', $this->arrtostart);
		// write contents to .htaccess
		file_put_contents($this->htaccessfile, $this->htaccesscontent);
		return '';
	}

	/**
	 * get last URL a client called
	 *
	 * @return	string		URI without some GET-vars (no_cache, purge_cache, L=0)
	 */
	private function getLastURL() {
		if (!isset($_SERVER['REQUEST_URI'])) {
			$serverrequri = $_SERVER['PHP_SELF'];
		} else {
			$serverrequri = $_SERVER['REQUEST_URI'];
		}

		$slcurrentPageName=str_replace('?&no_cache=1', '', $serverrequri);
		$slcurrentPageName=str_replace('?no_cache=1', '', $slcurrentPageName);
		$slcurrentPageName=str_replace('&no_cache=1', '', $slcurrentPageName);
		$slcurrentPageName=str_replace('?&purge_cache=1', '', $slcurrentPageName);
		$slcurrentPageName=str_replace('?purge_cache=1', '', $slcurrentPageName);
		$slcurrentPageName=str_replace('&purge_cache=1', '', $slcurrentPageName);
		$slcurrentPageName=str_replace('?&L=0', '', $slcurrentPageName);
		$slcurrentPageName=str_replace('&L=0', '', $slcurrentPageName);
		$slcurrentPageName=str_replace('?L=0', '', $slcurrentPageName);
		return $slcurrentPageName;
	}

	/**
	 * finish blocking process for IPs after check, database Update or Insert
	 *
	 * @param	array		$conf: ...
	 * @param	array		$ipinfo: ...
	 * @param	string		$ip: ...
	 * @param	boolean		$canUnblock: ...
	 * @param	int			$finishblockingforabuseipdb: ...
	 * @return	void		(sets $this->blockIP)
	 */
	protected function finishblocking($conf, $ipinfo, $ip, $canUnblock, $finishblockingforabuseipdb = 0) {
		if (($ipinfo['blockfe']+$ipinfo['blockhtaccess']) > 0) {
			$ipinfo['recurrence']++;

			$ipinfo['tstampunblock'] = time() +  ($conf['unblockAfterDays']*3600*24);
			$canUnblock = FALSE;
		} 
		
		if (($ipinfo['blockfe']+$ipinfo['blockhtaccess']+$ipinfo['blockforever']) == 0) {
			$ipinfo['tstampunblock'] = 0;
		}

		if ($finishblockingforabuseipdb == 1) {
			if ($ipinfo['recurrence'] >= intval($conf['blockByAbuseipdbCom.']['blockForEverRecurrence'])) {
				$ipinfo['blockforever'] = 1;
			}

		}

		if ($ipinfo['tstamp'] < (time()-10)) {
			$ipinfo['tstamp'] = time();
		}

		if ($ipinfo['uid'] == 0) {
			//INSERT
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_cyberpeace_iplist', array(
					'ipaddr' => $ip,
					'blockfe' => $ipinfo['blockfe'],
					'pid' => $ipinfo['pid'],
					'blockhtaccess' => $ipinfo['blockhtaccess'],
					'deleted' => 0,
					'blockforever' => $ipinfo['blockforever'],
					'crdate' => $ipinfo['crdate'],
					'tstamp' => $ipinfo['tstamp'],
					'tstampunblock' => $ipinfo['tstampunblock'],
					'recurrence' => $ipinfo['recurrence'],
					'comment' => $ipinfo['comment'],
					'isocode' => $ipinfo['isocode'],
					'country' => $ipinfo['country'],
					'category' => $ipinfo['category'],
					'abuseiplasttstamp' => $ipinfo['abuseiplasttstamp'],
					'accesscount' => $ipinfo['accesscount'],
					'lasturl' => $ipinfo['lasturl'],
					'blockingorigin' => $ipinfo['blockingorigin'],
			));
		} else {
			//UPDATE
			$GLOBALS['TYPO3_DB']->sql_query('UPDATE tx_cyberpeace_iplist SET ' .
					' deleted=0' .
					', tstampunblock=' . $ipinfo['tstampunblock'] .
					', tstamp=' . $ipinfo['tstamp'] .
					', comment="' . str_replace('"', '\"', $ipinfo['comment']) . '"' .
					', recurrence=' . $ipinfo['recurrence'] .
					', isocode="' . $ipinfo['isocode'] . '"' .
					', country="' . $ipinfo['country'] . '"' .
					', category="' . $ipinfo['category'] . '"' .
					', abuseiplasttstamp=' . $ipinfo['abuseiplasttstamp'] .
					', accesscount=' . $ipinfo['accesscount'] .
					', lasturl="' . $ipinfo['lasturl'] . '"' .
					', blockfe=' . $ipinfo['blockfe'] .
					', blockhtaccess=' . $ipinfo['blockhtaccess'] .
					', blockforever=' . $ipinfo['blockforever'] .
					', blockingorigin="' . $ipinfo['blockingorigin'] . '"' .
					' WHERE ipaddr="' . $ip . '"');

		}

		$blockIP = 0;

		if (($canUnblock == TRUE) || (($ipinfo['blockforever']+$ipinfo['blockhtaccess']) > 0)) {
			// check .htaccess
			$blockIP = 1;
			// get .htaccess
			$denyarr = $this->htaccessinitialize();
			if (is_array($denyarr)) {
				$cntdenys = count($denyarr);
				$foundhtIP = FALSE;
				$blockIP = 2;
				// Find IP
				for ($i=0; $i < $cntdenys; $i++) {
					if (trim($denyarr[$i]) == $ip) {
						$foundhtIP = TRUE;
						$blockIP = 3;
						// If present AND $canUnblock = TRUE AND comment="" remove it
						// If present AND blockhtaccess=0 remove it
						if ((($canUnblock == TRUE) && ($ipinfo['comment'] == '')) || ($ipinfo['blockhtaccess'] == 0)) {
							unset($denyarr[$i]);
							$blockIP = 4;
							break;
						}

					}

				}

				if ($foundhtIP == FALSE) {
					// If not present AND comment!="" add it
					if (($ipinfo['comment'] != '') && ($ipinfo['blockhtaccess'] == 1)) {
						$denyarr[$cntdenys] = $ip;
						$blockIP = 3;
					}

				}

				$this->htaccessfinalize($denyarr);
			}

		}

		$this->blockIP = $blockIP;
	}

	/**
	 * makes sure IP is in .htacess and then redirects to root of website
	 *
	 * @param	string		$ip: IP to check
	 * @param	boolean		$checkhtaccess: if .htaccss still needs to be checked before redirect
	 * @return	void		...exit();
	 */
	private function checkhtaccessandblock($ip, $checkhtaccess = TRUE) {

		if ($checkhtaccess == TRUE) {
			$denyarr = $this->htaccessinitialize();
			if (is_array($denyarr)) {
				$cntdenys = count($denyarr);
				$foundhtIP = FALSE;

				// Find IP
				for ($i=0; $i < $cntdenys; $i++) {
					if (trim($denyarr[$i]) == $ip) {
						$foundhtIP = TRUE;
						break;
					}

				}

				if ($foundhtIP == FALSE) {
					// If not present add it
						$denyarr[$cntdenys] = $ip;
				}

				$this->htaccessfinalize($denyarr);

			}

		}

		sleep(5);
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
		header('Location: /');
		exit;
	}

	/**
	 * checks an IP against abuseipb.com
	 *
	 * @param	array		$conf: ...
	 * @param	string		$ipaddr: ...
	 * @return	string		or array or reply from abuseipdb.com
	 * @access private
	 */
	private function checkIPabuse($conf, $ipaddr){
		$data ='';
		$infomessage = '';
		$abuseipdbserver = 'www.abuseipdb.com';

		if ($conf['testip'] != '') {
			$ipaddr = $conf['testip'];
		}

		if (!extension_loaded('curl')) {
			$infomessage = 'Curl, PHP-Problem: Curl extension is required!';
			$alertmsg = 1;
		} else {
			$ch = curl_init();
			$tuseragent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36';
			$urltofetch = 'https://'.$abuseipdbserver.'/check/'.$ipaddr.'/json?key='.$conf['blockByAbuseipdbCom.']['abuseipdbComAPIId'].'&days='.
							$conf['blockByAbuseipdbCom.']['abuseipdbComAPIcheckbackDays'] . '';

			curl_setopt($ch, CURLOPT_USERAGENT, $tuseragent);

 			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, 0);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_FILETIME, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TRANSFERTEXT, 1);
			curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);

			curl_setopt($ch, CURLOPT_URL, $urltofetch);
			$data = curl_exec($ch);
			$curl_errno = curl_errno($ch);

			if ($curl_errno > 0) {
				$curl_errmsg =  curl_error($ch);
				$infomessage = 'Curl, error reading "' . $urltofetch . '": ' . $curl_errmsg . ', code: ' . $curl_errno;
			} else {
				$infohttpcode = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
				// checking mime types
				if ($infohttpcode >= 400)  {
					$infomessage = 'Curl, returned code ' . $infohttpcode . ' for URL: ' . $urltofetch;
				}

			}

			curl_close($ch);
		}

		if ((trim($data) == '') && (trim($infomessage) == '')) {
			$infomessage = 'Curl, empty reply from ' . $abuseipdbserver;
		}

		if (trim($data) != '') {
			$ret = trim($data);
			$retarr = json_decode(trim($data), TRUE);
			return $retarr;
		} else {
			return $infomessage;
		}

	}

	/**
	 * returns the IP of the client
	 *
	 * @return	string
	 * @access private
	 */
	private function getRequestIP() {
		$requestIP = t3lib_div::getIndpEnv('REMOTE_ADDR');
		return $requestIP;
	}

	/**
	 * returns the blacklist array for the requesting IP
	 *
	 * @param	string		$ip	to query against the maxmind DB.
	 * @return	array
	 * @access private
	 */
	private function getBlacklistForIP($ip) {
		$returnValue = array();
		$this->hitip = '';
		$this->hitipcomment = '';
		if ($ip != '') {
			$returnValue = $this->checkTableBLs($ip);
		}

		return $returnValue;
	}

	/**
	 * Checks both table-based blocking lists.
	 *
	 * @param	string		$ipaddr	IP address
	 * @return	array		0: local TRUE if exists in the list, 1: static TRUE
	 */
	private function checkTableBLs($ipaddr) {
		$retstr = explode(',', intval($this->checkLocalBL($ipaddr)) . ',' . intval($this->checkStaticBL($ipaddr)));
		return $retstr;
	}

	/**
	 * Checks local blocking lists.
	 *
	 * @param	string		$ipaddr	IP address
	 * @return	boolean		TRUE if exists in the list
	 */
	private function checkLocalBL($ipaddr) {
		$parts = explode('.', $ipaddr);

		$recs = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('ipaddr, comment', 'tx_toctoc_comments_ipbl_local',
				'blockfe=1 AND ipaddr LIKE ' . '"%' . $parts[0] . '.' . $parts[1] . '.%"');

		foreach ($recs as $rec) {
			if (str_replace('.' . $parts[0] . '.' . $parts[1] . '.', '', $rec['ipaddr']) == $rec['ipaddr']){
				list($addr, $mask) = explode('/', $rec['ipaddr']);
				if ($mask == '') {
					if ($addr == $ipaddr) {
						$this->hitip = $rec['ipaddr'];
						$this->hitipcomment = $rec['comment'];
						return TRUE;
					}

				} else {
					$mask = 0xFFFFFFFF << (32 - $mask);
					if (trim(long2ip(ip2long($ipaddr) & $mask)) == trim($addr)) {
						$this->hitip = $rec['ipaddr'];
						$this->hitipcomment = $rec['comment'];
						return TRUE;
					}

				}

			}

		}

		return FALSE;
	}

	/**
	 * Checks static blocking lists.
	 *
	 * @param	string		$ipaddr	IP address
	 * @return	boolean		TRUE if exists in the list
	 */
	private function checkStaticBL($ipaddr) {
		$parts = explode('.', $ipaddr);
		$recs = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('ipaddr, comment', 'tx_toctoc_comments_ipbl_static',
				'ipaddr LIKE ' . '"%' . $parts[0] . '.' . $parts[1] . '.%"');

		foreach ($recs as $rec) {
			if (str_replace('.' . $parts[0] . '.' . $parts[1] . '.', '', $rec['ipaddr']) == $rec['ipaddr']){
				list($addr, $mask) = explode('/', $rec['ipaddr']);
				if ($mask == '') {
					if ($addr == $ipaddr) {
						$this->hitip = $rec['ipaddr'];
						$this->hitipcomment = $rec['comment'];
						return TRUE;
					}

				} else {
					$mask = 0xFFFFFFFF << (32 - $mask);
					if (trim(long2ip(ip2long($ipaddr) & $mask)) == trim($addr)) {
						$this->hitip = $rec['ipaddr'];
						$this->hitipcomment = $rec['comment'];
						return TRUE;
					}

				}

			}

		}

		return FALSE;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cyberpeace/pi1/class.tx_cyberpeace_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/cyberpeace/pi1/class.tx_cyberpeace_pi1.php']);
}
?>