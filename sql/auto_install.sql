-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_o8_iras_response_log`;
DROP TABLE IF EXISTS `civicrm_o8_iras_donation`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_o8_iras_donation
-- *
-- * IRAS Donation Reporting tool
-- *
-- *******************************************************/
CREATE TABLE `civicrm_o8_iras_donation` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `cdntaxreceipts_log_id` int COMMENT 'FK to Contact',
  `is_api` tinyint COMMENT 'api or offline report',
  `comment` text NULL COMMENT 'comment to sending item',
  `log_id` int unsigned NULL COMMENT 'FK to Contact Response log',
  `created_date` datetime COMMENT 'Created date',
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_o8_iras_donation_cdntaxreceipts_log_id FOREIGN KEY (`cdntaxreceipts_log_id`) REFERENCES `cdntaxreceipts_log`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_o8_iras_response_log
-- *
-- * iras log response logs
-- *
-- *******************************************************/
CREATE TABLE `civicrm_o8_iras_response_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID',
  `response_body` text NOT NULL COMMENT 'json response of request',
  `response_code` int NULL COMMENT 'response code',
  `created_date` datetime COMMENT 'Created date',
  PRIMARY KEY (`id`)
)
ENGINE=InnoDB;
