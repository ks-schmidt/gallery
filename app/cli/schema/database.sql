DROP DATABASE IF EXISTS `gallery`;
CREATE DATABASE `gallery`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`img_folder`;
CREATE TABLE `gallery`.`img_folder` (
  `id_folder` INT          NOT NULL AUTO_INCREMENT,
  `fk_folder` INT                   DEFAULT NULL,
  `path`      VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_folder`),
  CONSTRAINT `folder_fk_folder` FOREIGN KEY (`fk_folder`) REFERENCES `gallery`.`img_folder` (`id_folder`),
  UNIQUE INDEX `folder_path_UNIQUE` (`path` ASC)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`img_file`;
CREATE TABLE `gallery`.`img_file` (
  `id_file`     INT           NOT NULL AUTO_INCREMENT,
  `fk_folder`   INT                    DEFAULT NULL,
  `path`        VARCHAR(255)  NOT NULL,
  `name`        VARCHAR(255)  NOT NULL,
  `extension`   VARCHAR(255)  NOT NULL,
  `size`        INT           NOT NULL,
  `description` VARCHAR(255)  NOT NULL,
  `tag`         VARCHAR(2048) NOT NULL,
  CONSTRAINT `file_fk_folder` FOREIGN KEY (`fk_folder`) REFERENCES `gallery`.`img_folder` (`id_folder`),
  PRIMARY KEY (`id_file`)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`img_tag`;
CREATE TABLE `gallery`.`img_tag` (
  `id_tag` INT          NOT NULL AUTO_INCREMENT,
  `name`   VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_tag`)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`acl_role`;
CREATE TABLE `gallery`.`acl_role` (
  `id_acl_role` INT         NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_acl_role`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`acl_user`;
CREATE TABLE `gallery`.`acl_user` (
  `id_acl_user`  INT          NOT NULL AUTO_INCREMENT,
  `username`     VARCHAR(45)  NOT NULL,
  `password`     VARCHAR(255) NOT NULL,
  `email`        VARCHAR(255) NOT NULL,
  `token`        CHAR(16)     NULL,
  `token_expire` DATETIME     NULL,
  PRIMARY KEY (`id_acl_user`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`acl_user_acl_role`;
CREATE TABLE `gallery`.`acl_user_acl_role` (
  `id_acl_user_acl_role` INT NOT NULL AUTO_INCREMENT,
  `fk_acl_user`          INT NOT NULL,
  `fk_acl_role`          INT NOT NULL,
  PRIMARY KEY (`id_acl_user_acl_role`),
  UNIQUE INDEX `fk_acl_user_acl_role_UNIQUE` (`fk_acl_user` ASC, `fk_acl_role` ASC)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`acl_role_img_folder`;
CREATE TABLE `gallery`.`acl_role_img_folder` (
  `id_acl_role_img_folder` INT NOT NULL AUTO_INCREMENT,
  `fk_acl_role`            INT NOT NULL,
  `fk_folder`              INT NOT NULL,
  CONSTRAINT `acl_role_img_folder_fk_folder` FOREIGN KEY (`fk_folder`) REFERENCES `gallery`.`img_folder` (`id_folder`),
  CONSTRAINT `acl_role_img_folder_fk_acl_role` FOREIGN KEY (`fk_acl_role`) REFERENCES `gallery`.`acl_role` (`id_acl_role`),
  PRIMARY KEY (`id_acl_role_img_folder`)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `gallery`.`acl_role_img_file`;
CREATE TABLE `gallery`.`acl_role_img_file` (
  `id_acl_role_img_file` INT NOT NULL AUTO_INCREMENT,
  `fk_acl_role`          INT NOT NULL,
  `fk_file`              INT NOT NULL,
  CONSTRAINT `acl_role_img_file_fk_file` FOREIGN KEY (`fk_file`) REFERENCES `gallery`.`img_file` (`id_file`),
  CONSTRAINT `acl_role_img_file_fk_acl_role` FOREIGN KEY (`fk_acl_role`) REFERENCES `gallery`.`acl_role` (`id_acl_role`),
  PRIMARY KEY (`id_acl_role_img_file`)
);

INSERT INTO `gallery`.`acl_role` (`id_acl_role`, `name`) VALUES (1, 'admin');
INSERT INTO `gallery`.`acl_user` (`username`, `email`) VALUES ('karsten.schmidt', 'weggemopst@web.de');
INSERT INTO `gallery`.`acl_user_acl_role` (`fk_acl_user`, `fk_acl_role`) VALUES ('1', '1');
INSERT INTO `gallery`.`img_folder` (`id_folder`, `path`) VALUES ('1', '/');
INSERT INTO `gallery`.`acl_role_img_folder` (`fk_acl_role`, `fk_folder`) VALUES ('1', '1');
