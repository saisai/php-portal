UPDATE employee SET user_id = SUBSTRING_INDEX(email_id, '@', 1) AND password = password('msys@123')

ALTER TABLE `employee` ADD COLUMN `location` varchar(30)  NOT NULL AFTER `msys_email`;

concat(SUBSTRING_INDEX(id, '/', 1),'_',SUBSTRING_INDEX(id, '/', -1))

ALTER TABLE `employee` 
CHANGE COLUMN `address` `temp_address` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
CHANGE COLUMN `address_2` `perm_address` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `employee` 
CHANGE COLUMN `city` `temp_city` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
CHANGE COLUMN `state` `temp_state` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
ADD COLUMN `perm_city` VARCHAR(45) NOT NULL AFTER `temp_city`,
ADD COLUMN `perm_state` VARCHAR(45) NOT NULL AFTER `perm_city`;

ALTER TABLE `emp_leave_ledger` 
MODIFY COLUMN `description` text  CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
MODIFY COLUMN `remarks` text  CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

ALTER TABLE `emp_documents` 
ADD COLUMN `upload_date` date NOT NULL DEFAULT '0000-00-00' AFTER `doc_file`,
ADD COLUMN `hr_remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `returned`,
MODIFY COLUMN `remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `emp_qualification` 
ADD COLUMN `upload_date` date NOT NULL DEFAULT '0000-00-00' AFTER `grade`,
ADD COLUMN `hr_remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `upload_date`,
MODIFY COLUMN `remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `emp_experience` 
ADD COLUMN `upload_date` date NOT NULL DEFAULT '0000-00-00' AFTER `ctc`,
ADD COLUMN `hr_remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `upload_date`,
MODIFY COLUMN `remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `emp_visa` 
ADD COLUMN `upload_date` date NOT NULL DEFAULT '0000-00-00' AFTER `end_date`,
ADD COLUMN `hr_remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `upload_date`,
MODIFY COLUMN `remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `emp_projects` 
ADD COLUMN `upload_date` date NOT NULL DEFAULT '0000-00-00' AFTER `is_completed`,
ADD COLUMN `hr_remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `upload_date`,
MODIFY COLUMN `remarks` VARCHAR(240) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

CREATE TABLE `holidays` (
  `slno` int(30) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `day` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `location` varchar(45) NOT NULL,
  `is_optional` tinyint(1) NOT NULL,
  `remarks` varchar(45) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`slno`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1

INSERT INTO holidays (date,day,title,is_optional) VALUES
('2015-01-01','Thursday','New Year Day','0'),
('2015-01-15','Thursday','Pongal','0'),
('2015-01-26','Monday','Republic Day','0'),
('2015-04-15','Tuesday','Tamil New Year','0'),
('2015-05-01','Friday','May Day','0'),
('2015-09-17','Thursday','Vinayaka Chaturthi','0'),
('2015-10-02','Friday','Gandhi Jayanthi','0'),
('2015-10-21','Wednesday','Ayutha Pooja','0'),
('2015-11-10','Tuesday','Deepavali','0'),
('2015-12-25','Friday','Christmas Day','0'),
('2015-01-16','Friday','Tiruvalluvar Day','1'),
('2015-04-15','Friday','Good Friday','1'),
('2015-09-24','Thursday','Bakrid','1');


CREATE TABLE `circulars` (
  `slno` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL DEFAULT '',
  `location` varchar(100) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT 1,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`slno`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1

CREATE TABLE  `news_letter` (
  `slno` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `exp_date` date NOT NULL DEFAULT '0000-00-00',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `location` varchar(100) NOT NULL DEFAULT '',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`slno`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1