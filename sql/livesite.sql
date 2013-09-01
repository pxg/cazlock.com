-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-pow20
-- Generation Time: Sep 01, 2013 at 08:43 AM
-- Server version: 5.0.91
-- PHP Version: 4.4.9
-- 
-- Database: `livesite`
-- 
CREATE DATABASE `livesite` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `livesite`;

-- --------------------------------------------------------

-- 
-- Table structure for table `admin_users`
-- 

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL auto_increment,
  `role` enum('administrator','moderator','member') collate utf8_unicode_ci NOT NULL default 'member',
  `username` varchar(120) collate utf8_unicode_ci NOT NULL,
  `password` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `admin_users`
-- 

INSERT INTO `admin_users` VALUES (1, 'administrator', 'admin', 'da2a39e2b9b30136fabe9e7c2dc0c88369b347a0f4bf8b754f037e8221ae812b');

-- --------------------------------------------------------

-- 
-- Table structure for table `pages`
-- 

CREATE TABLE `pages` (
  `id` int(11) NOT NULL auto_increment,
  `is_trashed` tinyint(1) unsigned NOT NULL default '0',
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `body` text collate utf8_unicode_ci,
  `creation_date` datetime NOT NULL,
  `last_modified_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `is_trashed` (`is_trashed`),
  KEY `is_trashed_is_published` (`is_trashed`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `pages`
-- 

INSERT INTO `pages` VALUES (1, 0, 'home', 'Welcome', '<p>Hi, welcome to my portfolio. My name is Caz Lock and I''m an Illustrator based in London. I hope you take the time to look around and enjoy my work.</p>', '0000-00-00 00:00:00', '2013-02-06 12:49:30');
INSERT INTO `pages` VALUES (2, 0, 'About', 'About', '<p>Hi my name is Caz Lock and I&rsquo;m an Illustrator based in London.</p>\r\n<p>I was born on a sunny day in Brighton and I&rsquo;ve&nbsp;loved&nbsp;drawing for as  long as I can remember. At an early age I was always jealous that my  brother&rsquo;s drawing of Sonic was better than mine, which made me strive to improve my work, although nowadays a  wider range of wonderful things and people inspire me. Visiting  galleries, exhibitions and museums really helps me look at things from a  different perspective, which in turn aids me in finding inspiration in  the everyday. I&rsquo;m highly motivated and I enjoy working as part of a team just as much as I enjoy managing my own tasks.<br /> <br />I enjoy listening to an eclectic selection of music, am a  self-confessed console geek and often wind down by watching a wide  variety of animation.</p>\r\n<h2>Contact</h2>\r\n<p>If you think you would like to work with me then feel free to drop me a line through one of the following methods:</p>\r\n<p><em>Phone:</em>&nbsp; &nbsp;&nbsp;<strong>Please see my CV</strong><br /><em>Email:</em>&nbsp;&nbsp;&nbsp; <strong>hello (at) cazlock (dot) com</strong><br /><br /></p>', '0000-00-00 00:00:00', '2013-02-06 12:49:19');

-- --------------------------------------------------------

-- 
-- Table structure for table `project_image_types`
-- 

CREATE TABLE `project_image_types` (
  `id` int(11) NOT NULL auto_increment,
  `image_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `project_image_types`
-- 

INSERT INTO `project_image_types` VALUES (1, 'image');
INSERT INTO `project_image_types` VALUES (2, 'vimeo');
INSERT INTO `project_image_types` VALUES (3, 'flash');
INSERT INTO `project_image_types` VALUES (4, 'iframe');

-- --------------------------------------------------------

-- 
-- Table structure for table `project_images`
-- 

CREATE TABLE `project_images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `project_id` int(11) NOT NULL,
  `image_type_id` int(11) NOT NULL,
  `img_src` varchar(255) default NULL,
  `flash_src` varchar(255) default NULL,
  `vimeo_url` varchar(255) default NULL,
  `iframe_url` varchar(255) default NULL,
  `image_name` varchar(255) default NULL,
  `width` int(4) NOT NULL,
  `height` int(4) NOT NULL,
  `priority` int(11) default NULL,
  `is_hidden` tinyint(1) default '0',
  `is_trashed` tinyint(1) default '0',
  `creation_date` datetime default NULL,
  `last_modified_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=167 DEFAULT CHARSET=latin1 AUTO_INCREMENT=167 ;

-- 
-- Dumping data for table `project_images`
-- 

INSERT INTO `project_images` VALUES (29, 55, 1, 'project-image-xx-r.jpg', NULL, '', NULL, 'test', 680, 400, NULL, 0, 0, NULL, '2010-12-04 09:39:11');
INSERT INTO `project_images` VALUES (30, 56, 1, 'project-image-30-r1.jpg', NULL, '', NULL, 'pirate', 680, 245, 1, 0, 0, NULL, '2010-12-06 17:50:46');
INSERT INTO `project_images` VALUES (31, 56, 1, 'project-image-31-r.jpg', NULL, '', NULL, 'pirate', 680, 680, 0, 0, 0, NULL, '2010-12-06 17:50:46');
INSERT INTO `project_images` VALUES (37, 60, 1, 'project-image-xx-r8.jpg', NULL, '', NULL, 'Rayner', 680, 1225, NULL, 0, 0, NULL, '2010-12-12 11:19:33');
INSERT INTO `project_images` VALUES (32, 57, 1, 'project-image-xx-r3.jpg', NULL, '', NULL, 'Ammy Full', 680, 912, 0, 0, 0, NULL, '2010-12-06 17:53:04');
INSERT INTO `project_images` VALUES (33, 57, 1, 'project-image-xx-r4.jpg', NULL, '', NULL, 'Ammy Close', 680, 680, 1, 0, 0, NULL, '2010-12-06 17:53:04');
INSERT INTO `project_images` VALUES (34, 58, 1, 'project-image-xx-r5.jpg', NULL, '', NULL, 'Aristocats', 680, 459, NULL, 0, 0, NULL, '2010-12-05 16:01:20');
INSERT INTO `project_images` VALUES (35, 59, 1, 'project-image-xx-r6.jpg', NULL, '', NULL, 'Serene', 680, 378, NULL, 0, 0, NULL, '2010-12-05 18:04:30');
INSERT INTO `project_images` VALUES (36, 59, 1, 'project-image-xx-r7.jpg', NULL, '', NULL, 'Serene Close', 680, 680, NULL, 0, 0, NULL, '2010-12-05 18:05:20');
INSERT INTO `project_images` VALUES (38, 61, 1, 'project-image-xx-r9.jpg', NULL, '', NULL, 'Michelle', 680, 913, NULL, 0, 0, NULL, '2010-12-16 17:27:01');
INSERT INTO `project_images` VALUES (39, 62, 1, 'project-image-xx-r10.jpg', NULL, '', NULL, '', 680, 1118, NULL, 0, 0, NULL, '2010-12-16 17:38:09');
INSERT INTO `project_images` VALUES (40, 63, 1, 'project-image-xx-r11.jpg', NULL, '', NULL, 'Milliner Sketch', 680, 511, NULL, 0, 0, NULL, '2010-12-16 17:55:32');
INSERT INTO `project_images` VALUES (41, 64, 1, 'project-image-41-r.jpg', NULL, '', NULL, 'Thagya', 680, 957, NULL, 0, 0, NULL, '2011-01-06 14:37:11');
INSERT INTO `project_images` VALUES (42, 63, 1, 'project-image-xx-r13.jpg', NULL, '', NULL, 'photo', 680, 455, NULL, 0, 0, NULL, '2010-12-16 18:24:42');
INSERT INTO `project_images` VALUES (43, 63, 1, 'project-image-xx-r14.jpg', NULL, '', NULL, 'photo1', 680, 455, NULL, 1, 0, NULL, '2010-12-16 18:26:35');
INSERT INTO `project_images` VALUES (44, 65, 1, 'project-image-xx-r15.jpg', NULL, '', NULL, 'chllaxing', 680, 580, NULL, 0, 0, NULL, '2010-12-16 18:29:33');
INSERT INTO `project_images` VALUES (45, 65, 1, 'project-image-xx-r16.jpg', NULL, '', NULL, 'chillaxing 1', 680, 436, NULL, 0, 1, NULL, '2010-12-16 19:14:49');
INSERT INTO `project_images` VALUES (46, 65, 1, 'project-image-xx-r17.jpg', NULL, '', NULL, 'chillaxing 1', 680, 711, NULL, 0, 0, NULL, '2010-12-16 19:15:37');
INSERT INTO `project_images` VALUES (47, 62, 1, 'project-image-xx-r18.jpg', NULL, '', NULL, 'kll close', 680, 680, NULL, 0, 0, NULL, '2010-12-19 07:47:15');
INSERT INTO `project_images` VALUES (48, 66, 3, NULL, 'project-flash-xx-r.swf', '', NULL, 'Animation', 500, 488, NULL, 0, 0, NULL, '2010-12-19 07:59:57');
INSERT INTO `project_images` VALUES (49, 66, 1, 'project-image-xx-r19.jpg', NULL, '', NULL, 'illustration', 680, 651, NULL, 0, 0, NULL, '2010-12-19 08:01:36');
INSERT INTO `project_images` VALUES (50, 67, 1, 'project-image-xx-r20.jpg', NULL, '', NULL, 'hayley', 680, 451, NULL, 0, 0, NULL, '2010-12-19 09:17:21');
INSERT INTO `project_images` VALUES (51, 67, 1, 'project-image-xx-r21.jpg', NULL, '', NULL, 'hayley1', 680, 452, NULL, 0, 0, NULL, '2010-12-19 09:18:04');
INSERT INTO `project_images` VALUES (52, 67, 1, 'project-image-xx-r22.jpg', NULL, '', NULL, 'hayley2', 680, 496, NULL, 0, 0, NULL, '2010-12-19 09:18:57');
INSERT INTO `project_images` VALUES (53, 67, 1, 'project-image-xx-r23.jpg', NULL, '', NULL, 'hayley3', 680, 732, NULL, 0, 0, NULL, '2010-12-19 09:19:59');
INSERT INTO `project_images` VALUES (54, 58, 1, 'project-image-xx-r24.jpg', NULL, '', NULL, 'Kitty 1', 680, 680, NULL, 0, 0, NULL, '2010-12-19 09:49:18');
INSERT INTO `project_images` VALUES (55, 58, 1, 'project-image-xx-r25.jpg', NULL, '', NULL, 'kitty 2', 680, 680, NULL, 0, 0, NULL, '2010-12-19 09:50:40');
INSERT INTO `project_images` VALUES (56, 58, 1, 'project-image-xx-r26.jpg', NULL, '', NULL, 'Kitty 3', 680, 680, NULL, 0, 0, NULL, '2010-12-19 09:51:44');
INSERT INTO `project_images` VALUES (57, 68, 1, 'project-image-xx-r27.jpg', NULL, '', NULL, 'Ballerina', 680, 826, NULL, 0, 0, NULL, '2010-12-19 11:39:05');
INSERT INTO `project_images` VALUES (58, 68, 1, 'project-image-xx-r28.jpg', NULL, '', NULL, 'Ballerina close', 680, 1016, NULL, 0, 0, NULL, '2010-12-19 19:03:59');
INSERT INTO `project_images` VALUES (59, 69, 1, 'project-image-xx-r29.jpg', NULL, '', NULL, 'fireworks', 680, 425, NULL, 0, 0, NULL, '2011-01-05 12:18:32');
INSERT INTO `project_images` VALUES (60, 69, 1, 'project-image-xx-r30.jpg', NULL, '', NULL, 'fireworks 1', 680, 680, NULL, 0, 0, NULL, '2011-01-05 12:19:01');
INSERT INTO `project_images` VALUES (61, 69, 1, 'project-image-xx-r31.jpg', NULL, '', NULL, 'fireworks 2', 680, 680, NULL, 0, 0, NULL, '2011-01-05 12:19:40');
INSERT INTO `project_images` VALUES (62, 70, 1, 'project-image-xx-r32.jpg', NULL, '', NULL, 'Man Boob', 680, 1046, NULL, 0, 0, NULL, '2011-01-05 12:24:55');
INSERT INTO `project_images` VALUES (63, 71, 1, 'project-image-xx-r33.jpg', NULL, '', NULL, 'levitra', 680, 475, NULL, 0, 1, NULL, '2011-01-06 13:49:48');
INSERT INTO `project_images` VALUES (64, 71, 1, 'project-image-xx-r34.jpg', NULL, '', NULL, 'levitra 1', 680, 516, NULL, 0, 1, NULL, '2011-01-06 13:49:52');
INSERT INTO `project_images` VALUES (65, 71, 1, 'project-image-xx-r35.jpg', NULL, '', NULL, 'levitra 2', 680, 591, 2, 0, 1, NULL, '2011-01-06 14:11:08');
INSERT INTO `project_images` VALUES (66, 71, 1, 'project-image-xx-r36.jpg', NULL, '', NULL, 'levitra 3', 680, 689, 3, 0, 1, NULL, '2011-01-06 14:11:12');
INSERT INTO `project_images` VALUES (67, 71, 1, 'project-image-xx-r37.jpg', NULL, '', NULL, 'levitra 4', 680, 482, NULL, 0, 1, NULL, '2011-01-06 13:50:01');
INSERT INTO `project_images` VALUES (68, 72, 1, 'project-image-xx-r38.jpg', NULL, '', NULL, 'flux', 680, 881, NULL, 0, 0, NULL, '2011-01-05 13:25:39');
INSERT INTO `project_images` VALUES (69, 72, 1, 'project-image-xx-r39.jpg', NULL, '', NULL, 'flux 1', 680, 455, NULL, 0, 0, NULL, '2011-01-05 13:26:19');
INSERT INTO `project_images` VALUES (70, 72, 1, 'project-image-xx-r40.jpg', NULL, '', NULL, 'flux 2', 680, 1243, NULL, 0, 0, NULL, '2011-01-05 13:27:03');
INSERT INTO `project_images` VALUES (71, 73, 1, 'project-image-xx-r41.jpg', NULL, '', NULL, 'king bunny', 680, 1016, NULL, 0, 0, NULL, '2011-01-05 13:35:14');
INSERT INTO `project_images` VALUES (72, 73, 1, 'project-image-xx-r42.jpg', NULL, '', NULL, 'king bunny 1', 680, 1016, NULL, 0, 0, NULL, '2011-01-05 13:37:32');
INSERT INTO `project_images` VALUES (73, 73, 1, 'project-image-xx-r43.jpg', NULL, '', NULL, 'king bunny 2', 680, 455, NULL, 0, 0, NULL, '2011-01-05 13:37:13');
INSERT INTO `project_images` VALUES (74, 74, 1, 'project-image-74-r.jpg', NULL, '', NULL, 'tea', 680, 467, NULL, 0, 0, NULL, '2011-01-06 14:24:02');
INSERT INTO `project_images` VALUES (75, 74, 1, 'project-image-xx-r45.jpg', NULL, '', NULL, 'tea 1', 680, 1016, NULL, 0, 0, NULL, '2011-01-05 13:45:50');
INSERT INTO `project_images` VALUES (76, 75, 1, 'project-image-xx-r46.jpg', NULL, '', NULL, 'star', 680, 455, NULL, 0, 0, NULL, '2011-01-05 13:55:27');
INSERT INTO `project_images` VALUES (77, 75, 1, 'project-image-xx-r47.jpg', NULL, '', NULL, 'star 1', 680, 1016, NULL, 0, 0, NULL, '2011-01-05 13:56:39');
INSERT INTO `project_images` VALUES (78, 71, 1, 'project-image-78-r.jpg', NULL, '', NULL, 'levitra', 680, 378, 0, 0, 1, NULL, '2011-01-06 14:11:02');
INSERT INTO `project_images` VALUES (79, 71, 1, 'project-image-xx-r49.jpg', NULL, '', NULL, 'levitra 1', 680, 372, 1, 0, 1, NULL, '2011-01-06 14:11:05');
INSERT INTO `project_images` VALUES (80, 71, 1, 'project-image-xx-r50.jpg', NULL, '', NULL, 'levitra 4', 680, 472, 4, 0, 1, NULL, '2011-01-06 14:11:17');
INSERT INTO `project_images` VALUES (81, 71, 1, 'project-image-xx-r51.jpg', NULL, '', NULL, 'levitra', 680, 378, NULL, 0, 0, NULL, '2011-01-06 14:11:58');
INSERT INTO `project_images` VALUES (82, 71, 1, 'project-image-xx-r52.jpg', NULL, '', NULL, 'levitra 1', 680, 372, NULL, 0, 0, NULL, '2011-01-06 14:12:31');
INSERT INTO `project_images` VALUES (83, 71, 1, 'project-image-xx-r53.jpg', NULL, '', NULL, 'levitra 2', 680, 591, NULL, 0, 0, NULL, '2011-01-06 14:13:12');
INSERT INTO `project_images` VALUES (84, 71, 1, 'project-image-xx-r54.jpg', NULL, '', NULL, 'levitra 3', 680, 689, NULL, 0, 0, NULL, '2011-01-06 14:13:48');
INSERT INTO `project_images` VALUES (85, 71, 1, 'project-image-xx-r55.jpg', NULL, '', NULL, 'levitra 4', 680, 472, NULL, 0, 0, NULL, '2011-01-06 14:14:44');
INSERT INTO `project_images` VALUES (86, 76, 1, 'project-image-xx-r56.jpg', NULL, '', NULL, 'Lampades', 680, 1045, NULL, 0, 0, NULL, '2011-01-06 14:33:28');
INSERT INTO `project_images` VALUES (87, 77, 1, 'project-image-xx-r57.jpg', NULL, '', NULL, 'clean', 680, 576, NULL, 0, 0, NULL, '2011-01-06 17:52:41');
INSERT INTO `project_images` VALUES (88, 77, 1, 'project-image-xx-r58.jpg', NULL, '', NULL, 'cleaner', 680, 861, NULL, 0, 0, NULL, '2011-01-06 17:54:35');
INSERT INTO `project_images` VALUES (89, 77, 1, 'project-image-xx-r59.jpg', NULL, '', NULL, 'cleaner still', 680, 463, NULL, 0, 0, NULL, '2011-01-06 17:55:24');
INSERT INTO `project_images` VALUES (90, 78, 1, 'project-image-xx-r60.jpg', NULL, '', NULL, 'sex', 680, 396, 1, 0, 0, NULL, '2011-01-06 18:43:55');
INSERT INTO `project_images` VALUES (91, 78, 1, 'project-image-xx-r61.jpg', NULL, '', NULL, 'sex blog', 680, 564, 0, 0, 0, NULL, '2011-01-06 18:43:55');
INSERT INTO `project_images` VALUES (92, 78, 1, 'project-image-xx-r62.jpg', NULL, '', NULL, 'sex 1', 680, 452, 2, 0, 0, NULL, '2011-01-06 18:44:41');
INSERT INTO `project_images` VALUES (93, 79, 1, 'project-image-xx-r63.jpg', NULL, '', NULL, 'park', 680, 738, NULL, 0, 0, NULL, '2011-01-07 13:36:26');
INSERT INTO `project_images` VALUES (94, 79, 1, 'project-image-xx-r64.jpg', NULL, '', NULL, 'shop', 680, 553, NULL, 0, 0, NULL, '2011-01-07 13:37:12');
INSERT INTO `project_images` VALUES (95, 79, 1, 'project-image-xx-r65.jpg', NULL, '', NULL, 'snowboarding', 680, 367, NULL, 0, 0, NULL, '2011-01-07 13:37:57');
INSERT INTO `project_images` VALUES (96, 80, 1, 'project-image-xx-r66.jpg', NULL, '', NULL, 'bloodbeard', 680, 736, NULL, 0, 0, NULL, '2011-01-09 18:53:27');
INSERT INTO `project_images` VALUES (97, 81, 1, 'project-image-xx-r67.jpg', NULL, '', NULL, 'lobster', 680, 688, 1, 0, 1, NULL, '2011-02-19 13:44:18');
INSERT INTO `project_images` VALUES (98, 81, 1, 'project-image-xx-r68.jpg', NULL, '', NULL, 'manatee', 680, 637, 2, 0, 1, NULL, '2011-02-19 13:44:22');
INSERT INTO `project_images` VALUES (99, 81, 1, 'project-image-xx-r69.jpg', NULL, '', NULL, 'squid', 680, 700, 3, 0, 1, NULL, '2011-02-19 13:44:26');
INSERT INTO `project_images` VALUES (100, 81, 1, 'project-image-xx-r70.jpg', NULL, '', NULL, 'manta ray', 680, 616, 4, 0, 1, NULL, '2011-02-19 13:44:33');
INSERT INTO `project_images` VALUES (101, 81, 1, 'project-image-xx-r71.jpg', NULL, '', NULL, 'Badges', 680, 230, 0, 0, 0, NULL, '2011-02-19 13:42:36');
INSERT INTO `project_images` VALUES (102, 81, 1, 'project-image-xx-r72.jpg', NULL, '', NULL, 'Badges 1', 680, 230, 5, 1, 1, NULL, '2011-02-19 13:44:37');
INSERT INTO `project_images` VALUES (103, 81, 1, 'project-image-xx-r73.jpg', NULL, '', NULL, 'lobster', 680, 688, 1, 0, 0, NULL, '2011-02-19 13:45:35');
INSERT INTO `project_images` VALUES (104, 81, 1, 'project-image-xx-r74.jpg', NULL, '', NULL, 'manta ray', 680, 616, NULL, 0, 0, NULL, '2011-02-19 13:46:19');
INSERT INTO `project_images` VALUES (105, 81, 1, 'project-image-xx-r75.jpg', NULL, '', NULL, 'squid', 680, 700, NULL, 0, 0, NULL, '2011-02-19 13:47:01');
INSERT INTO `project_images` VALUES (106, 81, 1, 'project-image-xx-r76.jpg', NULL, '', NULL, 'manatee', 680, 637, NULL, 0, 0, NULL, '2011-02-19 13:47:47');
INSERT INTO `project_images` VALUES (107, 82, 1, 'project-image-xx-r77.jpg', NULL, '', NULL, 'bat country', 680, 962, NULL, 0, 0, NULL, '2011-07-16 10:36:55');
INSERT INTO `project_images` VALUES (108, 82, 1, 'project-image-xx-r78.jpg', NULL, '', NULL, 'One Rose', 680, 962, NULL, 0, 0, NULL, '2011-07-16 10:37:29');
INSERT INTO `project_images` VALUES (109, 82, 1, 'project-image-xx-r79.jpg', NULL, '', NULL, 'Energon', 680, 962, NULL, 1, 1, NULL, '2011-07-17 07:47:29');
INSERT INTO `project_images` VALUES (110, 82, 1, 'project-image-xx-r80.jpg', NULL, '', NULL, 'Energon', 680, 962, NULL, 0, 0, NULL, '2011-07-17 07:48:12');
INSERT INTO `project_images` VALUES (111, 83, 1, 'project-image-xx-r81.jpg', NULL, '', NULL, 'Rayner Full', 680, 572, NULL, 1, 0, NULL, '2012-01-16 16:21:31');
INSERT INTO `project_images` VALUES (112, 83, 1, 'project-image-xx-r82.jpg', NULL, '', NULL, 'Rayner Detail', 680, 680, NULL, 1, 0, NULL, '2012-01-16 16:21:29');
INSERT INTO `project_images` VALUES (113, 84, 1, 'project-image-xx-r83.jpg', NULL, '', NULL, 'Mermaid Full', 680, 688, NULL, 0, 0, NULL, '2012-01-14 18:55:18');
INSERT INTO `project_images` VALUES (114, 84, 1, 'project-image-xx-r84.jpg', NULL, '', NULL, 'Mermaid Detail 1', 680, 680, NULL, 0, 0, NULL, '2012-01-14 18:56:04');
INSERT INTO `project_images` VALUES (115, 84, 1, 'project-image-xx-r85.jpg', NULL, '', NULL, 'Mermaid Detail 2', 680, 680, NULL, 0, 0, NULL, '2012-01-14 18:56:43');
INSERT INTO `project_images` VALUES (116, 84, 1, 'project-image-xx-r86.jpg', NULL, '', NULL, 'Mermaid Detail 3', 680, 680, NULL, 0, 0, NULL, '2012-01-14 18:58:13');
INSERT INTO `project_images` VALUES (117, 83, 1, 'project-image-xx-r87.jpg', NULL, '', NULL, 'Rayner Full Edited', 680, 573, NULL, 0, 0, NULL, '2012-01-16 16:20:47');
INSERT INTO `project_images` VALUES (118, 83, 1, 'project-image-xx-r88.jpg', NULL, '', NULL, 'Rayner Detail Edited', 680, 680, NULL, 0, 0, NULL, '2012-01-16 16:21:25');
INSERT INTO `project_images` VALUES (119, 85, 1, 'project-image-xx-r89.jpg', NULL, '', NULL, 'hans', 680, 549, NULL, 0, 0, NULL, '2012-11-14 14:56:41');
INSERT INTO `project_images` VALUES (120, 86, 1, 'project-image-120-r.jpg', NULL, '', NULL, 'Miaka Floats', 680, 680, 0, 0, 0, NULL, '2012-11-14 15:17:53');
INSERT INTO `project_images` VALUES (121, 86, 1, 'project-image-xx-r90.jpg', NULL, '', NULL, 'Rayner Sword', 680, 859, 2, 0, 0, NULL, '2012-11-14 15:26:29');
INSERT INTO `project_images` VALUES (122, 86, 1, 'project-image-xx-r91.jpg', NULL, '', NULL, 'Shen Concept', 680, 864, 1, 0, 0, NULL, '2012-11-14 15:17:53');
INSERT INTO `project_images` VALUES (123, 86, 1, 'project-image-xx-r92.jpg', NULL, '', NULL, 'Miaka Design Sheet', 680, 673, 4, 0, 1, NULL, '2012-11-14 15:27:02');
INSERT INTO `project_images` VALUES (124, 86, 1, 'project-image-xx-r93.jpg', NULL, '', NULL, 'Rayner Design Sheet', 680, 680, 3, 0, 0, NULL, '2012-11-14 15:26:29');
INSERT INTO `project_images` VALUES (125, 86, 1, 'project-image-xx-r94.jpg', NULL, '', NULL, 'Miaka Design Sheet', 680, 673, 4, 0, 0, NULL, '2012-11-14 15:27:33');
INSERT INTO `project_images` VALUES (126, 87, 1, 'project-image-126-r.jpg', NULL, '', NULL, '', 680, 672, NULL, 0, 0, NULL, '2012-12-30 18:33:26');
INSERT INTO `project_images` VALUES (127, 88, 1, 'project-image-xx-r95.jpg', NULL, '', NULL, 'Shrigo Cowboys', 680, 510, 1, 0, 1, NULL, '2013-02-11 12:29:02');
INSERT INTO `project_images` VALUES (128, 88, 1, 'project-image-xx-r96.jpg', NULL, '', NULL, 'Shrigo Meditating', 680, 510, 2, 0, 1, NULL, '2013-02-11 12:29:07');
INSERT INTO `project_images` VALUES (129, 74, 2, NULL, NULL, '58893010', NULL, 'vimeo test', 500, 281, NULL, 0, 1, NULL, '2013-02-06 14:30:31');
INSERT INTO `project_images` VALUES (130, 88, 2, NULL, NULL, '58778161', NULL, 'Animation', 680, 380, 0, 0, 1, NULL, '2013-02-11 12:43:24');
INSERT INTO `project_images` VALUES (131, 88, 1, 'project-image-xx-r97.jpg', NULL, '', NULL, 'Freelancers', 680, 510, NULL, 0, 1, NULL, '2013-02-11 12:43:28');
INSERT INTO `project_images` VALUES (132, 88, 1, 'project-image-xx-r98.jpg', NULL, '', NULL, 'Cowboys', 680, 510, 1, 0, 1, NULL, '2013-02-11 12:44:59');
INSERT INTO `project_images` VALUES (133, 88, 1, 'project-image-xx-r99.jpg', NULL, '', NULL, 'Meditating', 680, 510, NULL, 0, 1, NULL, '2013-02-11 12:44:41');
INSERT INTO `project_images` VALUES (134, 88, 1, 'project-image-xx-r100.jpg', NULL, '', NULL, 'Meditating', 680, 510, 3, 0, 0, NULL, '2013-02-11 12:46:37');
INSERT INTO `project_images` VALUES (135, 88, 1, 'project-image-xx-r101.jpg', NULL, '', NULL, 'Cowboys', 680, 510, 1, 0, 0, NULL, '2013-02-11 14:48:53');
INSERT INTO `project_images` VALUES (136, 88, 1, 'project-image-xx-r102.jpg', NULL, '', NULL, 'Freelancers', 680, 510, 2, 0, 0, NULL, '2013-02-11 12:46:37');
INSERT INTO `project_images` VALUES (137, 88, 2, NULL, NULL, '58778161', NULL, 'Animation', 680, 380, 0, 0, 0, NULL, '2013-02-11 14:48:53');
INSERT INTO `project_images` VALUES (138, 89, 2, NULL, NULL, '58778161', NULL, '1', 680, 380, NULL, 0, 0, NULL, '2013-02-11 15:33:59');
INSERT INTO `project_images` VALUES (139, 89, 1, 'project-image-xx-r103.jpg', NULL, '', NULL, '2', 680, 510, NULL, 0, 0, NULL, '2013-02-11 15:32:13');
INSERT INTO `project_images` VALUES (140, 89, 1, 'project-image-xx-r104.jpg', NULL, '', NULL, '3', 680, 510, NULL, 0, 0, NULL, '2013-02-11 15:32:38');
INSERT INTO `project_images` VALUES (141, 89, 1, 'project-image-xx-r105.jpg', NULL, '', NULL, '4', 680, 510, NULL, 0, 0, NULL, '2013-02-11 15:33:03');
INSERT INTO `project_images` VALUES (142, 90, 1, 'project-image-xx-r106.jpg', NULL, '', NULL, 'Dawn Miaka', 680, 629, NULL, 0, 0, NULL, '2013-03-03 18:24:36');
INSERT INTO `project_images` VALUES (143, 90, 1, 'project-image-xx-r107.jpg', NULL, '', NULL, 'Miaka Close', 680, 680, NULL, 0, 0, NULL, '2013-03-03 18:25:30');
INSERT INTO `project_images` VALUES (144, 91, 1, 'project-image-xx-r108.jpg', NULL, '', NULL, 'Concept Art', 680, 669, 0, 0, 1, NULL, '2013-05-14 18:02:11');
INSERT INTO `project_images` VALUES (145, 91, 1, 'project-image-145-r.jpg', NULL, '', NULL, 'Walk', 680, 120, 2, 0, 1, NULL, '2013-05-14 18:01:36');
INSERT INTO `project_images` VALUES (146, 91, 1, 'project-image-xx-r109.jpg', NULL, '', NULL, 'Jump', 680, 453, 3, 0, 1, NULL, '2013-05-14 18:01:39');
INSERT INTO `project_images` VALUES (147, 91, 1, 'project-image-147-r.jpg', NULL, '', NULL, 'Title Screen', 680, 570, 1, 0, 1, NULL, '2013-05-14 18:00:54');
INSERT INTO `project_images` VALUES (148, 91, 1, 'project-image-xx-r110.jpg', NULL, '', NULL, 'Title', 680, 570, NULL, 0, 1, NULL, '2013-05-14 18:02:09');
INSERT INTO `project_images` VALUES (149, 91, 1, 'project-image-xx-r111.jpg', NULL, '', NULL, 'Concept Art', 680, 669, NULL, 0, 1, NULL, '2013-05-14 18:06:33');
INSERT INTO `project_images` VALUES (150, 91, 1, 'project-image-xx-r112.jpg', NULL, '', NULL, 'Title Screen', 680, 570, NULL, 0, 1, NULL, '2013-05-14 18:06:37');
INSERT INTO `project_images` VALUES (151, 91, 1, 'project-image-xx-r113.jpg', NULL, '', NULL, 'Walk', 680, 120, NULL, 0, 1, NULL, '2013-05-14 18:06:40');
INSERT INTO `project_images` VALUES (152, 91, 1, 'project-image-152-r.jpg', NULL, '', NULL, 'jump', 680, 453, NULL, 0, 1, NULL, '2013-05-14 18:06:43');
INSERT INTO `project_images` VALUES (153, 91, 1, 'project-image-xx-r114.jpg', NULL, '', NULL, 'Title Screen', 680, 592, NULL, 0, 0, NULL, '2013-05-14 18:13:13');
INSERT INTO `project_images` VALUES (154, 91, 1, 'project-image-xx-r115.jpg', NULL, '', NULL, 'Concept Art', 680, 669, NULL, 0, 0, NULL, '2013-05-14 18:13:45');
INSERT INTO `project_images` VALUES (155, 91, 1, 'project-image-xx-r116.jpg', NULL, '', NULL, 'Walk/Punch', 680, 214, NULL, 0, 0, NULL, '2013-05-14 18:14:25');
INSERT INTO `project_images` VALUES (156, 91, 1, 'project-image-xx-r117.jpg', NULL, '', NULL, 'Jump', 680, 453, NULL, 0, 0, NULL, '2013-05-14 18:15:39');
INSERT INTO `project_images` VALUES (158, 92, 1, 'project-image-xx-r118.jpg', NULL, '', '', 'Pin Up Pirate', 680, 1101, NULL, 0, 0, NULL, '2013-06-08 17:26:17');
INSERT INTO `project_images` VALUES (159, 92, 1, 'project-image-xx-r119.jpg', NULL, '', '', 'Close Up', 680, 680, NULL, 0, 0, NULL, '2013-06-08 17:26:54');
INSERT INTO `project_images` VALUES (160, 92, 1, 'project-image-xx-r120.jpg', NULL, '', '', 'Sketch', 680, 1214, NULL, 0, 0, NULL, '2013-06-08 17:27:38');
INSERT INTO `project_images` VALUES (161, 93, 1, 'project-image-xx-r121.jpg', NULL, '', '', 'Lumberjack', 680, 622, NULL, 0, 0, NULL, '2013-08-13 12:21:43');
INSERT INTO `project_images` VALUES (162, 93, 1, 'project-image-xx-r122.jpg', NULL, '', '', 'Lumberjack Close', 680, 680, NULL, 0, 0, NULL, '2013-08-13 12:22:18');
INSERT INTO `project_images` VALUES (163, 93, 1, 'project-image-xx-r123.jpg', NULL, '', '', 'Smoking', 680, 622, NULL, 0, 0, NULL, '2013-08-13 12:22:51');
INSERT INTO `project_images` VALUES (164, 93, 1, 'project-image-xx-r124.jpg', NULL, '', '', 'Smoking Close', 680, 680, NULL, 0, 0, NULL, '2013-08-13 12:24:09');
INSERT INTO `project_images` VALUES (165, 93, 1, 'project-image-xx-r125.jpg', NULL, '', '', 'Weightlifter', 680, 622, NULL, 0, 0, NULL, '2013-08-13 12:23:58');
INSERT INTO `project_images` VALUES (166, 93, 1, 'project-image-xx-r126.jpg', NULL, '', '', 'Weightlifter Close', 680, 680, NULL, 0, 0, NULL, '2013-08-13 12:24:43');

-- --------------------------------------------------------

-- 
-- Table structure for table `projects`
-- 

CREATE TABLE `projects` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `project_title` varchar(255) NOT NULL,
  `project_slug` varchar(255) NOT NULL,
  `project_thumb` varchar(255) NOT NULL,
  `project_description` text NOT NULL,
  `project_date` datetime NOT NULL,
  `featured_image` varchar(255) default NULL,
  `priority` int(11) unsigned default '0',
  `is_featured` tinyint(1) default '0',
  `is_hidden` tinyint(1) NOT NULL default '0',
  `is_trashed` tinyint(1) default '0',
  `creation_date` datetime NOT NULL,
  `last_modified_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=94 DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

-- 
-- Dumping data for table `projects`
-- 

INSERT INTO `projects` VALUES (54, 'Fearless', 'fearless', 'uploaded-thumb-54-r.jpg', '<p>Entry for the word "Fearless" at <a href="http://www.illusrationfriday.com">Illustration Friday</a>.</p>\r\n<p>Sketched in pencil, inked and coloured in Photoshop.</p>', '2010-05-05 00:00:00', 'featured-image-xx-r.jpg', 0, 1, 0, 1, '0000-00-00 00:00:00', '2010-11-23 02:48:51');
INSERT INTO `projects` VALUES (55, 'test', 'test', 'uploaded-thumb-55-r.jpg', '<p>test</p>', '2010-07-09 00:00:00', 'featured-image-55-r.jpg', 3, 1, 0, 1, '0000-00-00 00:00:00', '2010-12-01 13:52:58');
INSERT INTO `projects` VALUES (56, 'Fearless', 'fearless', 'uploaded-thumb-xx-r.jpg', '<p>Entry for the word Fearless on <a href="http://www.illusrationfriday.com">Illustration Friday</a>.</p>\r\n<p>Sketched in pencil, inked and coloured in Photoshop.</p>', '2010-05-09 00:00:00', NULL, 16, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-05 14:53:45');
INSERT INTO `projects` VALUES (57, 'Amaterasu - Human Form', 'amaterasu', 'uploaded-thumb-xx-r1.jpg', '<p>Okami fan art.</p>\r\n<p>Sketched in pencil, inked and coloured in Photoshop.<br /> <br />I obtained the logo and background images as part of a Okami Fan Art  contest, held on Deviant Art a while ago, so thanks to Clover/Capcom and <a href="http://www.deviantart.com"> Deviant Art</a> for supplying them.</p>\r\n<p>Okami and Amaterasu belong to Capcom/Clover Studio''s.</p>', '2010-07-08 00:00:00', 'featured-image-xx-r.jpg', 24, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-05 15:04:23');
INSERT INTO `projects` VALUES (58, 'Aristocats', 'aristocats', 'uploaded-thumb-xx-r2.jpg', '<p>Acrylic Paint on Canvas.<br />Thanks to James Booth for the great photo of the piece. Visit his amazing Flickr page <a href="http://www.flickr.com/photos/jamesbooth/">here</a>.</p>', '2010-08-03 00:00:00', 'featured-image-xx-r1.jpg', 25, 0, 0, 0, '0000-00-00 00:00:00', '2010-12-05 15:59:48');
INSERT INTO `projects` VALUES (59, 'Serene', 'serene', 'uploaded-thumb-xx-r3.jpg', '<p>This illustration was completed to accompany my Pirate piece <a href="http://cazlock.com/projects/fearless">&ldquo;Fearless&rdquo;</a>.</p>\r\n<p>Sketched in pencil, inked and coloured in Photoshop.</p>', '2010-05-19 00:00:00', NULL, 31, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-05 18:03:31');
INSERT INTO `projects` VALUES (60, 'Rayner Mezame', 'rayner-mezame', 'uploaded-thumb-xx-r4.jpg', '<p>Original character design.</p>\r\n<p>Sketched in pencil, inked and coloured in Photoshop.</p>', '2010-05-01 00:00:00', NULL, 29, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-12 11:18:00');
INSERT INTO `projects` VALUES (61, 'Michelle''s Birthday Card', 'michelles-card', 'uploaded-thumb-xx-r5.jpg', '<p>Portrait of a friend for her birthday card.</p>\r\n<p>Watercolours and Ink</p>', '2009-11-18 00:00:00', NULL, 23, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-16 17:25:34');
INSERT INTO `projects` VALUES (62, 'Kids Love Lies - Under the Bed, Single Cover', 'kids-love-lies-under-the-bed', 'uploaded-thumb-xx-r6.jpg', '<p>I was commissioned by the band <a href="http://www.myspace.com/kidslovelies">Kids Love Lies</a> to create a cover for a single release.</p>\r\n<p>Pencil, and Photoshop.</p>', '2009-08-18 00:00:00', NULL, 18, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-16 17:36:55');
INSERT INTO `projects` VALUES (63, 'Milliner Business Card', 'milliner-business-card', 'uploaded-thumb-xx-r7.jpg', '<p>I was commissioned by a Milliner to do an illustration for her business cards.</p>\r\n<p>Sketched in pencil, inked in Photoshop.</p>', '2009-06-11 00:00:00', NULL, 26, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-16 17:54:09');
INSERT INTO `projects` VALUES (64, 'Descend from the Heavens', 'descend-from-the-heavens', 'uploaded-thumb-xx-r8.jpg', '<p>Original character.</p>\r\n<p>Inked and painted in Photoshop.</p>', '2009-05-20 00:00:00', NULL, 28, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-16 18:04:07');
INSERT INTO `projects` VALUES (65, 'Chillaxing', 'chillaxing', 'uploaded-thumb-xx-r9.jpg', '<p>I made this canvas as a birthday present for my brother.</p>\r\n<p>Acrylic paint, and Faber Castell waterproof marker on Canvas.<br /> Photography by <a href="http://www.flickr.com/photos/jamesbooth/">James Booth</a>.</p>', '2009-12-17 00:00:00', NULL, 27, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-16 18:18:12');
INSERT INTO `projects` VALUES (66, 'Me Wantz Moneeze', 'me-wantz-moneeze', 'uploaded-thumb-xx-r10.jpg', '<p>Mechanical pencil, inked and coloured in Adobe Photoshop. Animated in Flash.</p>', '2009-04-16 00:00:00', NULL, 19, 0, 0, 0, '0000-00-00 00:00:00', '2010-12-19 07:56:27');
INSERT INTO `projects` VALUES (67, 'Hayley''s Converse', 'hayleys-converse', 'uploaded-thumb-xx-r11.jpg', '<p>Commission for a Christmas present.</p>\r\n<p>Acrylic paint with outlines done in waterproof pens.</p>', '2008-12-04 00:00:00', 'featured-image-67-r.jpg', 15, 0, 0, 0, '0000-00-00 00:00:00', '2010-12-19 09:16:12');
INSERT INTO `projects` VALUES (68, 'Ballerina', 'ballerina', 'uploaded-thumb-xx-r12.jpg', '<p>Acyrlic Paint on Canvas.</p>', '2010-12-16 00:00:00', 'featured-image-68-r.jpg', 20, 0, 1, 0, '0000-00-00 00:00:00', '2010-12-19 11:37:15');
INSERT INTO `projects` VALUES (69, 'Fireworks', 'fireworks', 'uploaded-thumb-xx-r13.jpg', '<p>Sketched in biro. Illustrator CS3 and Photoshop CS3.</p>', '2008-10-02 00:00:00', NULL, 8, 0, 0, 0, '0000-00-00 00:00:00', '2011-01-05 12:17:30');
INSERT INTO `projects` VALUES (70, 'Viva la Man Boob!', 'viva-la-man-boob', 'uploaded-thumb-xx-r14.jpg', '<p>A friend asked me to draw a design for a Super Hero that he had come up with called Man Boob.</p>\r\n<p>Sketched in pencil. Inked and coloured in Photoshop CS3.</p>', '2008-07-01 00:00:00', NULL, 30, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-05 12:23:48');
INSERT INTO `projects` VALUES (71, 'Levitra', 'levitra', 'uploaded-thumb-xx-r15.jpg', '<p>This is freelance work I did for a pitch for a company called Levitra.</p>\r\n<p>The brief was to draw a series of characters that would be likeable and seem approachable to their target audience.</p>\r\n<p>Sketched in Mechanical Pencil, inked in fine liner.</p>', '2008-03-02 00:00:00', NULL, 4, 0, 0, 0, '0000-00-00 00:00:00', '2011-01-05 13:03:13');
INSERT INTO `projects` VALUES (72, 'Flux', 'flux', 'uploaded-thumb-xx-r16.jpg', '<p>This was part of a commission for the O2 Concept Store, inside the O2 Arena.</p>\r\n<p>Acrylic Paint. and Adobe Illustrator. <br /> Photography by <a href="http://www.flickr.com/photos/jamesbooth/">James Booth</a>.</p>', '2008-02-02 00:00:00', NULL, 32, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-05 13:24:11');
INSERT INTO `projects` VALUES (73, 'King Bunny of the WC', 'king-bunny', 'uploaded-thumb-xx-r17.jpg', '<p>This was part of a commission for the O2 Concept Store, inside the O2 Arena.</p>\r\n<p>Acrylic Paint.</p>', '2008-01-06 00:00:00', NULL, 22, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-05 13:34:02');
INSERT INTO `projects` VALUES (74, 'Tea', 'tea', 'uploaded-thumb-xx-r18.jpg', '<p>This was part of a commission for the O2 Concept Store, inside the O2 Arena.</p>\r\n<p>Adobe Illustrator and Acrylic Paint and Wallpaper on Cupboard doors.<br /> Photography by <a href="http://www.flickr.com/photos/jamesbooth/">James Booth</a>.</p>', '2007-02-24 00:00:00', NULL, 21, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-05 13:43:15');
INSERT INTO `projects` VALUES (75, 'Star Cloud', 'star-cloud', 'uploaded-thumb-xx-r19.jpg', '<p>This was part of a commission for the O2 Concept Store, inside the O2 Arena.</p>\r\n<p>Acrylic Paint, Emulsion Paint and Permanent Marker.<br /> Photography by <a href="http://www.flickr.com/photos/jamesbooth/">James Booth</a>.</p>', '2007-01-20 00:00:00', NULL, 33, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-05 13:53:39');
INSERT INTO `projects` VALUES (76, 'Lampades', 'lampades', 'uploaded-thumb-xx-r20.jpg', '<p>Character design for a Lampade or Nymph of the Underworld.</p>\r\n<p>Sketched in pencil. Inked in Photoshop CS3.</p>', '2007-09-10 00:00:00', NULL, 36, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-06 14:30:05');
INSERT INTO `projects` VALUES (77, 'Clean and Clear', 'clean-and-clear', 'uploaded-thumb-77-r.jpg', '<p>Illustrations for a pitch for Clean and Clear.</p>\r\n<p>The brief was to produce a set of illustrations for an educational skincare tutorial.</p>\r\n<p>Adobe Illustrator.</p>', '2010-02-17 00:00:00', NULL, 6, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-06 15:28:54');
INSERT INTO `projects` VALUES (78, 'COI - Sexual Health', 'coi-sexual-health', 'uploaded-thumb-xx-r22.jpg', '<p>Freelance illustration work on a pitch for the COI.</p>\r\n<p>I was briefed to bring to life an idea for a campaign to promote sexual health.</p>\r\n<p>Pencil and pens.</p>', '2009-08-22 00:00:00', NULL, 9, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-06 18:35:57');
INSERT INTO `projects` VALUES (79, 'COI - Languages', 'coi-languages', 'uploaded-thumb-xx-r23.jpg', '<p>Freelance work on a pitch for the COI.</p>\r\n<p>I was briefed to do a series of illustrations for a campaign to get children involved in learning new languages.</p>\r\n<p>Sketched in pencil, inked in fine liner and coloured in Photoshop.</p>', '2008-01-20 00:00:00', NULL, 34, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-07 13:34:30');
INSERT INTO `projects` VALUES (80, 'Bubbly Bloodybeard', 'bubbly-bloodybeard', 'uploaded-thumb-xx-r24.jpg', '<p>Sketched in Mechanical Pencil, inked and coloured in Photoshop</p>', '2007-11-17 00:00:00', NULL, 35, 0, 1, 0, '0000-00-00 00:00:00', '2011-01-09 17:23:35');
INSERT INTO `projects` VALUES (81, 'Sea Creature Badge Set', 'sea-creature-badge-set', 'uploaded-thumb-xx-r25.jpg', '<p>Illustrations for a badge set.</p>\r\n<p>Sketched in pencil, inked and coloured in Photoshop.</p>', '2011-01-09 00:00:00', 'featured-image-xx-r3.jpg', 3, 0, 0, 0, '0000-00-00 00:00:00', '2011-01-09 18:56:34');
INSERT INTO `projects` VALUES (82, 'Film Quotes', 'film-quotes', 'uploaded-thumb-xx-r26.jpg', '<p>Illustrations of quotes from 3 of my favourite films.</p>\r\n<p>Illustrator and Photoshop</p>', '2011-07-16 00:00:00', 'featured-image-82-r.jpg', 5, 0, 0, 0, '0000-00-00 00:00:00', '2011-07-16 10:35:55');
INSERT INTO `projects` VALUES (83, 'Evening Rayner', 'evening-rayner', 'uploaded-thumb-xx-r27.jpg', '<p>My original character Rayner. This image was inpsired by Art Nouveau.</p>\r\n<p>Selected by Imagine FX Magazine as their <a href="http://beta.imaginefx.com/image-day-65930" target="_blank" title="Image of the day 22/02/13">Image of the Day 22/01/2013</a>.</p>\r\n<p>Sketched in pencil, coloured in Photoshop and Illustrator.</p>', '2011-08-17 00:00:00', 'featured-image-83-r.jpg', 10, 0, 0, 0, '0000-00-00 00:00:00', '2011-08-17 19:33:00');
INSERT INTO `projects` VALUES (85, 'Hans', 'hans', 'uploaded-thumb-xx-r28.jpg', '<p>Acrylic Paint on Canvas.<br />Thanks to James Booth for the great photo of the piece. Visit his amazing Flickr page&nbsp;<a href="http://www.flickr.com/photos/jamesbooth/">here</a>.</p>', '2011-10-31 00:00:00', NULL, 13, 0, 1, 0, '0000-00-00 00:00:00', '2012-11-14 14:55:10');
INSERT INTO `projects` VALUES (84, 'Basking in the Sun', 'basking-in-the-sun', 'uploaded-thumb-84-r.jpg', '<p>Experimenting with new techniques.</p>\r\n<p>Photoshop.</p>', '2012-01-14 00:00:00', 'featured-image-84-r.jpg', 14, 0, 0, 0, '0000-00-00 00:00:00', '2012-01-14 18:53:38');
INSERT INTO `projects` VALUES (86, 'Concept Art for Comic', 'concept-art-for-comic', 'uploaded-thumb-86-r.jpg', '<p>Concept artwork and character designs for a comic I''m currently in the process of starting.</p>\r\n<p>This is an ongoing project. Work dates from early 2012 through to present time and is displayed with the earliest work first.</p>', '2012-11-04 00:00:00', 'featured-image-xx-r5.jpg', 17, 0, 0, 0, '0000-00-00 00:00:00', '2012-11-14 15:12:36');
INSERT INTO `projects` VALUES (87, 'Merry Christmas', 'merry-christmas', 'uploaded-thumb-xx-r29.jpg', '<p>Merry Christmas and a Happy New Year!</p>', '2012-12-24 00:00:00', 'featured-image-xx-r6.jpg', 11, 0, 1, 0, '0000-00-00 00:00:00', '2012-12-30 18:31:24');
INSERT INTO `projects` VALUES (88, 'YunoJuno Animation', 'yunojuno-animation', 'uploaded-thumb-xx-r30.jpg', '<p>Illustration work for YunoJuno for an animation about what the company do.</p>\r\n<p>Animation by <a href="http://www.jamesbooth.net" target="_blank">James Booth</a>.</p>\r\n<p>Adobe Illustrator</p>', '2013-02-06 00:00:00', 'featured-image-xx-r7.jpg', 0, 0, 0, 1, '0000-00-00 00:00:00', '2013-02-06 12:47:21');
INSERT INTO `projects` VALUES (89, 'YunoJuno Animation', 'yunojuno-animation', 'uploaded-thumb-xx-r31.jpg', '<p>Concepted and illustrated an introduction animation for&nbsp;<a href="http://yunojuno.com" target="_blank" title="YunoJuno">YunoJuno</a>.</p>\r\n<p>Animation by&nbsp;<a href="http://www.jamesbooth.net" target="_blank">James Booth</a>.</p>\r\n<p>Adobe Illustrator.</p>', '2013-02-08 00:00:00', 'featured-image-xx-r8.jpg', 1, 1, 0, 0, '0000-00-00 00:00:00', '2013-02-11 15:30:52');
INSERT INTO `projects` VALUES (90, 'Dawn Miaka', 'dawn-miaka', 'uploaded-thumb-xx-r32.jpg', '<p>Miaka my original character, this image was inpsired by Art Nouveau.</p>\r\n<p>Sketched in pencil, coloured in Photoshop and Illustrator.</p>', '2013-03-03 00:00:00', 'featured-image-xx-r9.jpg', 12, 0, 0, 0, '0000-00-00 00:00:00', '2013-03-03 18:23:45');
INSERT INTO `projects` VALUES (91, 'Kong''s Jungle', 'kongs-jungle', 'uploaded-thumb-xx-r33.jpg', '<p>Art work, and character design for an HTML 5/Canvas platform game.</p>\r\n<p><a href="http://www.kongsjungle.co.uk" target="_blank" title="Play Kong''s Jungle">Play&nbsp;Kong''s Jungle</a></p>\r\n<p>Photoshop.</p>', '2013-05-14 00:00:00', 'featured-image-xx-r10.jpg', 2, 1, 0, 0, '0000-00-00 00:00:00', '2013-05-14 17:02:40');
INSERT INTO `projects` VALUES (92, 'Pin Up Pirate', 'pin-up-pirate', 'uploaded-thumb-xx-r34.jpg', '<p>I was asked by a friend to design him a tattoo.</p>\r\n<p>His brief was a Pin Up Day of the Dead Pirate girl.</p>\r\n<p>Read "The Making of" in my blog:<br /><a href="http://blog.cazlock.com/post/50919743761/making-of-pin-up-pirate-tattoo-part-1" target="_blank" title="Part 1">Part 1</a> | <a href="http://blog.cazlock.com/post/52491918627/the-making-of-pin-up-pirate-tattoo-part-2" target="_blank" title="Part 2">Part 2</a><br /><br /></p>\r\n<p>Sketched in pencil, inked and coloured in Photoshop.</p>', '2013-06-08 00:00:00', 'featured-image-xx-r11.jpg', 7, 0, 0, 0, '0000-00-00 00:00:00', '2013-06-08 17:25:06');
INSERT INTO `projects` VALUES (93, 'Real Men', 'real-men', 'uploaded-thumb-xx-r35.jpg', '<p>A series of illustrations based on "Real Men".</p>\r\n<p>Adobe Illustrator</p>', '2013-08-13 00:00:00', 'featured-image-xx-r12.jpg', 0, 1, 0, 0, '0000-00-00 00:00:00', '2013-08-13 12:11:33');
