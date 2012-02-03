# Hanya

**A rapid and lightweight engine for small websites**

__Hanya brings simple html to the next level.__

At the moment Hanya needs at least PHP 5.3 to be installed, i'm refactoring it to work with PHP 5.2.

## Installation

1. Download the repository as [archive](https://github.com/256dpi/Hanya/zipball/master) and extract it in your working directory.

2. Edit the _RewriteBase_ parameter in the _.htaccess_ file to fit your server or local configuration.

3. Set the rights of the _system_ directory and _user/db.sq3_ to _0777_. The _db.sq3_ file gets created after the first request to the system.

4. Alter the default configuration in the _index.php_.

If you encountering problems check the _Configuration_ section to run Hanya in debuge mode.

## Basic Usage

The main feature of Hanya is the simple request router. Each request will be mapped to an physical html file in the _tree_ directory which holds the content of this page.

	"http://www.domain.com/directory/page" = "tree/directory/page.html"
	
In these html files you can place your code for the page. At default each page will be wrapped with the _elements/templates/default.html_ template.
To alter this behavior you can add meta informations to the top of the page file:

	//--
	title = "My Page Title"
	template = "another"
	--//
	<p>content of my page</p>
	...
	
The _template_ meta information will tell Hanya to load the _elements/templates/another.html_ to wrap the page.

A default template will look like this:

	<!doctype html>
	<html>
	  <head>
	    <meta charset="utf-8" />
	    <title>$meta(title)</title>
	    {head()}
	    <link rel="stylesheet" href="{asset(style.css)}" type="text/css" media="screen">
	  </head>
	  <body>
	    {toolbar()}
	    {block(content)}
	  </body>
	</html>
	
All the unusual markup is Hanya System Markup:

* The `$meta(title)` variable will replace it self with the value set in the meta informa1tion of the page.

* The `{head()}` tag renders system css and js includes to make the system working properly. _jQuery_ is included as default.

* With `{asset(style.css)}` you have an shortcut to files in the _assets_ directory.

* One of the most important tags is the `{toolbar()}` tag. It will render the login button and after login the hanya admin toolbar.

* At the position of `{block(content)}` your page markup will magically apear.

As you will see the Hanya system gives you handy variables and tag markup to enhance your boring html sites.

## Directory Structure

The working directory of your Hanya installation consists of the following directories:

* **assets** (all image, stylsheet and javascript files goes in this directory)
* **elements** (place your mail templates, error pages, partials and layouts in their subdirectories)
* **system** (don't touch this!)
* **tree** (your site tree)
* **uploads** (all upload over the system goes here)
* **user** (the DB, your definitions, translations, plugins and tags)

## Configuration

Hanya is a system that will run out of the box. But some features needs some configuration.

All configuration is set in the _index.php_ file in the `Hanya::run(...)` statement.

At default Hanya creates an SQLite databse in _user/db.sq3_ to store all db related data. For the small sites you are building with Hanya a SQLite db is enough. Furthermore you have on special advantage to an SQL Database. If you upload or download your project, you never again have to dump or load your SQL Database. If you still want to connect to an SQL Server use this configuration:

	"db.driver" => "mysql"
	"db.location" => "mysql:dbname=hanya;unix_socket=/tmp/mysql.sock"
	"db.user" => "root"
	"db.password" => "toor"
	
Check the [PHP Manual for PDO connections](http://www.php.net/manual/de/pdo.construct.php) to see available methods.

Hanya has a builtin _I18n_ translation system, but at the moment you can run the system only in one language. I don't knowh if there will be multiple language feature soon. However define your languages (one at the moment) and set it as default.

	"i18n.languages" => array("de"=>array("timezone"=>"Europe/Berlin","locale"=>"de_CH")),
	"i18n.default" => "de",
	
An englisch and german translation is built in for all system messages. Check the _Translation_ section to read about creating your own translations.

To give your customers action the admin toolbar add their credentials:

	"auth.users" => array("admin"=>"admin"),
	
Currently there are no roles for users, so it will be enought to create one user.

If you want to run the system in debug mode issue this configuration:

	"system.debug" => false
	
Hanya will create not existing tables în database automatically if they are needed. You can disable this beahvior:

	"system.automatic_db_setup" => false
	
The configuration options for the mailing system are covered in their section.

## 3rd Party Software

* ORM Class idiorm by j4mie (http://github.com/j4mie/idiorm/)

* Dynamic Point Function inspired by Kohana Routes (https://github.com/kohana/kohana)

* Filetype Icons by teambox (https://github.com/teambox/Free-file-icons/tree/master/16px)

* Icons from yusukekamiyaman (https://github.com/yusukekamiyamane/fugue-icons)

##License

Hanya is released under the MIT Licesne

Copyright (c) 2011 Joël Gähwiler

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.