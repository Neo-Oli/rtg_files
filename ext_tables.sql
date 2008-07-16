#
# Table structure for table 'tx_rtgfiles_files'
#
CREATE TABLE tx_rtgfiles_files (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	fe_group varchar(100) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	system int(11) DEFAULT '0' NOT NULL,
	description text NOT NULL,
	keywords text NOT NULL,
	file blob NOT NULL,
	url varchar(255) DEFAULT '' NOT NULL,
	clicks int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_rtgfiles_systems'
#
CREATE TABLE tx_rtgfiles_systems (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title varchar(80) DEFAULT '' NOT NULL,
	description text NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);