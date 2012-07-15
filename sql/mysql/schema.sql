DROP TABLE IF EXISTS `blend_invitations`;
CREATE TABLE `blend_invitations` (
  `code` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `accepted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`code`)
) DEFAULT CHARSET=utf8;