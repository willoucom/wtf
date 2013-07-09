wtf
===

Wilfried True Framework

aka Wilfried Tremendous Framework
aka Wilfried Typical Framework
aka Wilfried Tender Framework
aka Wilfried Terrified Framework

A simple framework using MVC the silly way
Coded in bad english by a frenchy

Requirements :
==
- Php 5.3+
- Mysql 5+
- Smarty 3+
- Programming skills

Installation :
==

- Copy files
- Copy external lib : Smarty into libs/Smarty/ (or make a simlink)
- Change /cache/ to be writeable
- Create mysql database
- Insert documentation/creation.sql into database
- Edit config.inc.php
- Point your browser to the project dir
- You can log in !! (user/pass is w/w) // security level : master

How this work ?
==
Magic !
or php code

web/inc/header.inc.php do the most of the job (get values and stuff)

web/inc/renderer.inc.php do the rendering using smarty or json

for each page.php you have to create a page.tpl into views

ProTips :
==
- Disabling authentification by simply remove the lines in web/inc/header.inc.php: 
<pre>
require($config['basedir'].'/models/Utilisateur.class.php');
$utilisateur = new Utilisateur($dbh,$traduction);
$messages = $utilisateur->verifie_connexion($messages);
</pre>

- Adding pages
copy/paste an existing page/view

- json ? what about json ?
simple, yourpage.php?m=json aaaaannnd magic !
you can change the renderer.inc.php if you want to use another rendering engine (plain php for example)
