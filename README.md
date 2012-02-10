# Hanya

**A rapid and lightweight engine for small websites**

__Hanya brings simple html to the next level.__

At the moment Hanya needs at least PHP 5.3 to be installed, i'm refactoring it to work with PHP 5.2.

## Installation

1. Download the repository as [archive](https://github.com/256dpi/Hanya/zipball/master) and extract it in your working directory.

2. Edit the `RewriteBase` parameter in the _.htaccess_ file to fit your server or local configuration.

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

* The `$meta(title)` variable will replace it self with the value set in the meta information of the page.

* The `{head()}` tag renders system css and js includes to make the system working properly. _jQuery_ is included as default.

* With `{asset(style.css)}` you have a shortcut to files in the _assets_ directory.

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
	
An english and german translation is built in for all system messages. Check the _Translation_ section to read about creating your own translations.

To give your customers access to the admin toolbar, add their credentials:

	"auth.users" => array("admin"=>"admin"),
	
Currently there are no roles for users, so it will be enought to create one user.

If you want to run the system in debug mode issue this configuration:

	"system.debug" => false
	
Hanya will create not existing tables automatically if they are needed. You can disable this beahvior:

	"system.automatic_db_setup" => false
	
The configuration options for the mailing system are covered in their section.

Hanya has a builtin sitemap generator which creates a sitemap.xml depending on your tree directory structure. To turn it off issue:

	"system.sitemap_generation" => false

## Meta Information

Sometimes you need a information about your page in the layout to render as example the `<title>..</title>` tag. With meta informations you can describe these variables in your page and use them later in your layout:

	//---
	title = "My page title"
	subtitle = "Another important title"
	//--
	
Now use them in your layout or partial:

	...
	<title>$meta(title)</title>
	...
	<h2>$meta(subtitle)</h2>
	...

## Tags

The Hanya System Markup in curly braces like `{toolbar()}` is a Hanya Tag which gets proccessed on each request.

There is a bunch of other system tags:

### Basic Tags

* `{head()}` will render the system css and javascript includes.

* `{toolbar()}` will output the Hanya login button or if logged in the Hanya admin toolbar.

* `{anchor(title|url)` is turning into `<a href="url">title</a>`. Additionally it will add a _link-current_ css class if the url matches the current request url or it will add a _link-active_ class if the url matches a part of the url (from the beginning).

* `{link(path)}` will return the path to a page.

* `{asset(path)}` will return the path to this asset.

* `{upload(path)}` will return the path to this upload.

### Helper Tags

* `{attach(block|mode|(path/html))}` lets you add html markup or load a file into an block, which can be render later with the block tag. To append html markup to an block use: `{attach(block(my-block|html|<p>some html markup</p>))}`. If you want to append the content of a partial use: `{attach(my-block|file|my-partial)}`. This will append the content of _elements/partials/my-partial.html_.

* `{block(name)}` renders the given block at this position. The content of your page ist se to the _content_ block.

* `{include(partial)}` loads and renders a partial like _elements/partials/partial.html_

* `{if(a|b|true|false)}` compares the first two arguments. If the comparison is true the third argument will be rendered otherwise the fourth argument or nothing.

### Dynamic Tags

* `{html(id)}` loads editable html markup from the database.

* `{text(id)}` loads editable text from the database.

* `{string(id)}` loads an editable string from the database.

* `{new(definition|arguments...)}` renders a link to create a new definition object. This will be covered later.

## Conditions

With Conditions you can check variables of existence and truness. The following meta information is set in a page file:

	...
	subnavigation = false
	...
	
Check it in your layout:

	...
	[?($meta(subnavigation))]
		<div id="subnavigation">
			...
		</div>
	[?:]
		... (else part)
	[/?]
	...
	
Important is when you nest conditions add an minus for each level of nesting before the question mark:

	...
	[?($meta(condition1)))]
		...
	[?:]
		[-?($meta(condition2)))]
			...
		[-?:]
	[/?]
	...

## Dynamic Points

To this point hanya has a static routing where request will be mapped to its physical files. To get more dynamicness _Dynamic Points_ will break that pattern and give you a handy function to create dynamic urls. You will need this functionalty only with the _Definition System_ but i will explain it first. Issue the following url pattern:

	http://www.domain.com/news/a-nice-looking-slug-for-my-news
	
The simplest way ist to create a _tree/news/a-nice-looking-slug-for-my-news.html_ file with the content for your news. But i will be more cool to have the news stored in a DB to push them into a template an render it. The best way is to tell Hanya your dynamic url patterns as dynamic points. Add the following statements to the _index.php_ above the `Hanya::run(...` statement:

	Hanya::dynamic_point("news","(/<slug>)");
	
This statement tells hanya to search for _news_ (first argument) at the beginning of your url and use the remainig url after the _/_ as a variable (second argument). The request will be automatically mapped to the _tree/news.html_ (first argument). In this file you get a new magical variable for the slug:

	<p>You requested the news: $dynamic(slug)</p>
	
Use this variable in combination with the _Definition System_.

You can also add more difficult patterns:

	Hanya::dynamic_point("company/news","(/<category>(/<slug>(/<part>)))");

Hanya renders the page of the first argument even if there is no remaining url. The default vaule all variables will be _NULL_.

## Definitions

The _Definition System_ is the greates part of the whole Hanya story it let you create models (definitions) of objects which can loaded and rendered in your pages. The best thing is that all content can be edited by your customer in the page itself. The only thing you have to carry about is your definition, the rest will be handled by Hanya.

### Basics

In the example above we created a dynamic point for our news, now we want to create a definition for that:

Create _user/definitions/news.php_ with the following content:

	class News_Definition extends Definition {

		public $blueprint = array(
			"title" => array("as"=>"string"),
			"slug" => array("as"=>"string"),
			"intro" => array("as"=>"text"),
			"full" => array("as"=>"html"),
		);

	}
	
This class explains your definitions blueprint to Hanya. On the next request Hanya will create a table with fields to store the data. Now we want to render a list of news in our _tree/news.html_:

	<h1>Actual News</h1>
	[news()]
		<h2>$news(title)</h2>
		<p>$news(intro)</h2>
		<p><a href="{link(news/$news(slug))}">Read more</a></p>
	[/news]
	{new(news)}

The defined partial will be render for each item in your news table and creates a list of your news with a link to its item. The `{new(news)}` tag will render a button which will be render in "page editing" mode to let the user create a new news object. The admin functionalities will be covered later in this documentation.

### Overloading

To render our news item in the page we need to overload the _load_ method of the definition to load only one item by a slug:

	public function load($definition,$arguments) {
		if(count($arguments == 2 && $arguments[0] == "item-by-slug")) {
			// Return Item
			$table = ORM::for_table($definition)->where("slug",$arguments[1]);
			return Helper::each_as_array($table->find_many());
		} else {
			// Return List
			$table = ORM::for_table($definition)->order_by_asc("ordering");
			return Helper::each_as_array($table->find_many());
		}
	}
	
If we pass the argument _item-by-slug_ and the slug we get our item, else all items from the table are returned.

### All in One

The rendering of a news item is also set in the _tree/news.html_ page file. So we need to check if there is an slug added to the url to view the item or when not view the news list. To cover this issue we use a condition to check for the `$new(slug)` variables availability. We extend the example from above with this code:

	[?($news(slug))]
		[news(item-by-slug|$news(slug))]
			<h1>$news(title)</h1>
			$news(full)
		[/news]
	[?:]
		<h1>Actual News</h1>
		[news()]
			<h2>$news(title)</h2>
			<p>$news(intro)</h2>
			<p><a href="{link(news/$news(slug))}">Read more</a></p>
		[/news]
		{new(news)}
	[/?]
	
The condition will check our _slug_ variable and if it exists it will render the first part of the loop. If the variable is null the other part gets renderd.

### Field Types

Use this field types to make your definitions fancy:

	"string" => array("as"=>"string")
	"text" => array("as"=>"text")
	"html" => array("as"=>"html")
	"textile" => array("as"=>"textile") // textile editor not supported yet!
	"boolean" => array("as"=>"boolean")
	"number" => array("as"=>"number")
	"time" => array("as"=>"time")
	"date" => array("as"=>"date")
	"selection" => array("as"=>"selection","options"=>array(""=>"None","opt1"=>"Option 1","opt2"=>"Option 2","opt3"=>"Option 3"))
	"reference" => array("as"=>"reference","definition"=>"my-definition","field"=>"value")
	"file" => array("as"=>"file","folder"=>".","blank"=>true,"upload"=>true) // will add a name_path magic variable too
	
### Ordering

All definitions have a _ordering_ field as default to let the user order them with up and down arrows. You can disable ordering with:

	public $orderable = false;
	
As default the ordering will treat all items if a table in a group. If you have as example news in categories and you want a ordering structure for each category. So you need to set the _groups_ variable:

	public $groups = array("category");
	
### Advanced Overloading

You can also overload the _create_ function of your definition. Check this sample definition:

	class Image_Definition extends Definition {

		public $groups = array("owner");

		public $blueprint = array(
			"title" => array("as"=>"string"),
			"file" => array("as"=>"file","blank"=>false,"folder"=>"images","upload"=>true),
			"owner" => array("as"=>"string","hidden"=>true)
		);

		public function create($entry,$argument) {
			$entry->owner = $argument;
			return $entry;
		}

		public function load($definition,$arguments) {
			$table = ORM::for_table($definition)->order_by_asc("ordering")->where("owner",$arguments[0]);
			return Helper::each_as_array($table->find_many());
		}

	}
	
You can pass arguments to the create function by adding them to the _new_ tag:

	{new(image|my-owner-a)}
	
### Events

To make your definitions more complex you can use the events methods to add more logic to your definitions:

	public function before_create($entry) {
		$entry->field = "override value";
		return $entry;
	}
	
	public function before_update($entry) {
		$entry->field = "override value";
		return $entry;
	}
	
	public function before_destroy() {
		return true;
	}
	
### Specials

There are some more options you can set in your _definition_ class:

	// is definition managed by hanya? (renders edit functionality)
	public $managed = true;
	
	// is object destroyable?
	public $destroyable = true;
	
	// the default config for each field (if you have a hundred of string fields)
	public $default_config = array(
		"hidden" => false,
		"label" => true,
		"validation" => array(), // validation is not supported at the moment
	);
	
### Translation

By creating your first definition you will see that you have to create your own translation files for field labels and buttons in the admin interface. Check the Translation section for moren information.

## Mailing

Hanya has a integrated mailing system. You can easiy create contact or ordering forms in html and create a template for the email. You have to specify your forms in the _index.php_:

	mail.sender = "hanya@example.com",
	mail.forms = array(
		"contact" => array("reciever"=>"mail@example.com","subject"=>"The Subject")
	)
	
Then create a form on a page:

	<form action="?command=mailer" method="post">
	  <input type="hidden" name="form" value="contact" />
	  <label for="mail[name]">Name</label>
	  <input name="mail[name]" />
	  <label for="mail[subject]">Subject</label>
	  <input name="mail[subject]" />
	  <label for="mail[message]">Message</label>
	  <textarea name="mail[message]"></textarea>
	  <input type="submit" value="Send" />
	</form>
	
Create your mail template _elements/mails/contact.html_:

	<h1>Contact Request from <strong>$mail(name)</strong></h1>
	<h2>Subject: $mail(subject)</h2>
	<p>$mail(message)</p>
	
I think if you read the previous sections, you will understand the exmaples. ;)

## Admin Toolbar

...

## Updating

...

## Translations

* System Translation
* Definition Translation

## Extending the System

### Tags

### Plugins

## System Workflow

...

## Constants

For benchmaring there are two constants which you can use:

	<p> Generation Time: #{HANYA_GENERATION_TIME} - Memory Peak: #{HANYA_MEMORY_PEAK}</p>

## 3rd Party Software

* ORM Class idiorm by j4mie (http://github.com/j4mie/idiorm/)

* Dynamic Point Function inspired by Kohana Routes (https://github.com/kohana/kohana)

* Filetype Icons by teambox (https://github.com/teambox/Free-file-icons/tree/master/16px)

* Icons from yusukekamiyaman (https://github.com/yusukekamiyamane/fugue-icons)

##License

Hanya is released under the MIT License

Copyright (c) 2011 Joël Gähwiler

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.