CREATE DATABASE sample_test;

use sample_test;

CREATE TABLE repositories (
	repository_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	repo_id VARCHAR(100) NOT NULL,
	url TEXT,
	date TIMESTAMP
);

CREATE TABLE packages (
	package_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(100) NOT NULL,
	date TIMESTAMP
);

CREATE TABLE Repository_packages_junction (
  repository_id int,
  package_id int,
  CONSTRAINT repo_pack_pk PRIMARY KEY (repository_id, package_id),
  CONSTRAINT FK_Repo
      FOREIGN KEY (repository_id) REFERENCES repositories (repository_id),
  CONSTRAINT FK_Pack
      FOREIGN KEY (package_id) REFERENCES packages (package_id),
  date TIMESTAMP
);
