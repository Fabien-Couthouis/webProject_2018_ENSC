create database if not exists myforms character set utf8 collate utf8_unicode_ci;
use myforms;

grant all privileges on myforms.* to 'myforms_user'@'localhost' identified by 'mdp';