<?php

return [
    //Create new table
    'create_records' => "
CREATE TABLE records (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	link CHAR(40),
	image CHAR(40) NOT NULL,
	title CHAR(40) NOT NULL,
	description TEXT,
	iduser INTEGER NOT NULL DEFAULT 0,
	active NUMERIC NOT NULL DEFAULT 1
);

    ",


    //Fill table records some data
    'fill_records' => "
INSERT INTO records (link, image, title, description)
	VALUES
		('accusamus_architecto','img_01.jpg','Accusamus architecto', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus architecto aspernatur, autem consectetur dicta ipsa laboriosam libero magni maxime minus neque nisi praesentium quam tempore tenetur voluptate voluptates? Minima, volupta!');
    ",


    //Create new table
    'create_users' => "
CREATE TABLE users (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	name CHAR(40) NOT NULL,
	email VARCHAR(60),
	login VARCHAR(60) NOT NULL,
	password VARCHAR(60) NOT NULL,
	active  NUMERIC NOT NULL DEFAULT 1
);
    ",


    //Fill table records some data
    'fill_users' => "
INSERT INTO users (name, login, password)
	VALUES
		('Cer Lins', 'user', 'user');
    "

];