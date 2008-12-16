#
# Table structure for table 'tx_communityflexiblelayout_dashboardconfig'
#
CREATE TABLE tx_communityflexiblelayout_dashboardconfig (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    community_id tinytext NOT NULL,
    profile_type tinytext NOT NULL,
    profile_id int(11) DEFAULT '0' NOT NULL,
    configuration text NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);

#
# Table structure for table 'fe_users'
#
CREATE TABLE fe_users (
	tx_communityflexiblelayout_lotteryonly int(4) DEFAULT '0' NOT NULL,
);

