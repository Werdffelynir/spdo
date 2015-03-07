

-- new table
CREATE TABLE snippets (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	link CHAR(40),
	tags CHAR(100),
	title CHAR(40) NOT NULL,
	description TEXT,
	iduser INTEGER NOT NULL DEFAULT 0,
	idcategory INTEGER NOT NULL DEFAULT 0,
	idsubcategory INTEGER NOT NULL DEFAULT 0,
	active NUMERIC NOT NULL DEFAULT 1
);
INSERT INTO snippets (title, description) 
	VALUES 
		('First snippets', 'Content to first snippets'),
		('Second snippets', 'Content to second snippets'),
		('Third snippets', 'Content to thrid snippets'),
		('Fourth snippets', 'Content to fourth snippets'),
		('Fifth snippets', 'Content to fifth snippets');


-- new table
CREATE TABLE users (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	name CHAR(40) NOT NULL,
	email VARCHAR(60),
	login VARCHAR(60) NOT NULL,
	password VARCHAR(60) NOT NULL,
	active  NUMERIC NOT NULL DEFAULT 1
);
INSERT INTO users (name, login, password) 
	VALUES 
		('O.Werd', 'werd', '000000');


-- new table
CREATE TABLE category (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	link CHAR(40),
	title CHAR(40)
);
INSERT INTO category (title) 
	VALUES 
		('html'),
		('javascript'),
		('php'),
		('sql'),
		('apache'),
		('nginx'),
		('actionscript');


-- new table
CREATE TABLE subcategory (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	idcategory INTEGER NOT NULL,
	link CHAR(40),
	title CHAR(40)
);