#
# Table structure for table 'tx_cyberpeace_iplist'
#

CREATE TABLE tx_cyberpeace_iplist (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(1) unsigned DEFAULT '0' NOT NULL,
	ipaddr varchar(255) DEFAULT '' NOT NULL,
	country varchar(255) DEFAULT '' NOT NULL,
	isocode varchar(255) DEFAULT '' NOT NULL,
	category varchar(255) DEFAULT '' NOT NULL,
	abuseiplasttstamp int(11) DEFAULT '0' NOT NULL,
	blockfe tinyint(1) unsigned DEFAULT '0' NOT NULL,
	blockhtaccess tinyint(1) unsigned DEFAULT '0' NOT NULL,
	tstampunblock int(11) DEFAULT '0' NOT NULL,
	blockforever tinyint(1) unsigned DEFAULT '0' NOT NULL,
	recurrence int(11) DEFAULT '0' NOT NULL,
	accesscount int(11) DEFAULT '0' NOT NULL,
	lasturl varchar(255) DEFAULT '' NOT NULL,
	blockingorigin varchar(255) DEFAULT '' NOT NULL,
	comment text,
	PRIMARY KEY (uid),
	KEY xparent (pid),
	UNIQUE KEY xipaddr (ipaddr(50))
);

#
# Table structure for table 'tx_cyberpeace_dynopt'
#

CREATE TABLE tx_cyberpeace_dynopt (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	dynopttstamp int(11) DEFAULT '0' NOT NULL,
	dynopt varchar(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (uid),
	KEY xdynopt (dynopt(50))
);

#
# Table structure for table 'tx_cyberpeace_log'
#

CREATE TABLE tx_cyberpeace_log (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(1) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	totalips int(11) DEFAULT '0' NOT NULL,
	allowedips int(11) DEFAULT '0' NOT NULL,
	allowedcrawlers int(11) DEFAULT '0' NOT NULL,
	blockedcrawlers int(11) DEFAULT '0' NOT NULL,
	blockedbylocalbl int(11) DEFAULT '0' NOT NULL,
	blockedbyimportedbl int(11) DEFAULT '0' NOT NULL,
	blockedbyabuseipdb int(11) DEFAULT '0' NOT NULL,
	blockedbysyslog int(11) DEFAULT '0' NOT NULL,
	counthtaccess int(11) DEFAULT '0' NOT NULL,
	unblockedwaitforrecheck int(11) DEFAULT '0' NOT NULL,
	logcomment text,
	PRIMARY KEY (uid),
	KEY xcrdate (crdate)
);