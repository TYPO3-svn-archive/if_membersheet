#
# Table structure for table 'tx_ifmembersheet_member'
#
CREATE TABLE tx_ifmembersheet_member (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	first_name varchar(30) DEFAULT '' NOT NULL,
	last_name varchar(50) DEFAULT '' NOT NULL,
	birthdate int(11) DEFAULT '0' NOT NULL,
	hobbies text NOT NULL,
	occupations int(11) DEFAULT '0' NOT NULL,
	picture blob NOT NULL,
	text text NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_ifmembersheet_occupation'
#
CREATE TABLE tx_ifmembersheet_occupation (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	since int(11) DEFAULT '0' NOT NULL,
	parentid int(11) DEFAULT '0' NOT NULL,
	parenttable tinytext NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);
