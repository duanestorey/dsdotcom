---
title: "One WordPress Install, Multiple Sites"
publishDate: "2009-05-07"
modifiedDate: "2009-05-07"
slug: "one-wordpress-install-multiple-sites"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "blogs"
  - "Redirect"
  - "Single Install"
  - "Sites"
  - "Wordpress"
---

A while ago I started messing around with trying to get a single WordPress installation to host multiple blogs. If you read that and think WordPress MU, you’re not far off. What I don’t like about WordPress MU though is that the system administrator chooses the themes and the plugins that are available to the end user — ideally, I would want each user to control that themselves, such that it basically has the same functionality as a normal WordPress installation.

There are a lot of other reasons why you might want a single WordPress install for multiple blogs. First, if you make backups of each blog’s data from time to time, you might end up with a complete WordPress package for each website you host, even though ultimately 90% of those files are identical (basically only themes, plugins and custom content vary). Second, if you run a hosting server with a PHP caching engine (which most do), it’s likely that the cache keeps track of data using the complete path to the file, which ultimately means the cache effectiveness will decrease proportional to the number of sites (aka WordPress installations). If all the installations on a server shared one common WordPress install, you’d only have to cache that one set of PHP files — effectively you could keep WordPress in a compiled state in memory for all of your sites.

The first thing I did was install a fresh WordPress into a directory on my server called wp\_install. Normally an end user would go through the installation process and configure that one installation, and then they’d have one blog. We’re trying to make one installation work for many blogs.

Next, I created a wp\_domains directory at the same level as wp\_install. Inside wp\_install, I then created a symbolic link called “domains” which points to the wp\_domains directory (you could also just put everything in the wp\_install/domains directory, but I want to keep them separated slightly). So we have wp\_install/domains symbolically linking to wp\_domains.

In order to make one installation work for many sites, you basically need to have a dynamic wp-config.php file that can change depending on which site is being accessed. So I started messing with wp-config.php to try and come up with something that would work. The end goal was to change the parameters in the wp-config.php ssuch that we can use site-specific content in the wp\_domains directory.

I ended up with something like this:

`$website = strtolower( str_replace( "www.", "", $_SERVER["SERVER_NAME"] ) );<br></br>$website = preg_replace('[^a-z0-9\.-]', '', $website );`

define( 'WP\_CONTENT\_DIR', dirname(\_\_FILE\_\_) . '/domains/' . $website . '/wp-content' );  
define( 'WP\_CONTENT\_URL', 'http://' . $\_SERVER\["SERVER\_NAME"\] . '/domains/' . $website . '/wp-content' );

if ( file\_exists( dirname(\_\_FILE\_\_) . "/../wp\_domains/$website/db.config" ) ) {  
 require\_once( dirname(\_\_FILE\_\_) . "/../wp\_domains/$website/db.config" );  
} else {  
 echo "Sorry, no configuration defined."  
 die;  
}

Several things happen in the code, so let me try to explain. First, the destination website is determined using the HTTP server name which is passed in the address. For this website, that becomes www.duanestorey.com. I strip off the www just so that there’s a bit of consistency between sites. Next, I tell WordPress to change its default wp-content directory to the domains/WEBSITE\_NAME/wp-content directory, which in my case is domains/duanestorey.com/wp-content.

In the next section of code, I check for domains/WEBSITE\_NAME/db.config, which is a site-specific file that will set the database information on a per-site basis. If it exists, we load it up such that the username, password, hostname, and database name for that particular site.

An example of db.config for my site is:

`<?php <br /?>define( 'DB_NAME', 'duanestorey-com' );<br></br>define( 'DB_HOST', 'localhost' );<br></br>define( 'DB_PASSWORD', 'user' );<br></br>define( 'DB_USER', 'password' );<br></br>?>`

At this point, everything worked fine, and I had a base WordPress installation where the wp-content directory and database information could be split out dynamically. From a hosting perspective, you could conceivably only grant access to that one directory (i.e. wp\_domains/duanestorey.com), which would allow the user to modify their plugins and themes for their site only (even though the core WordPress files are being shared).

It’s at this point I actually decided to do a Google search to see if anyone else had tried this approach before. I found [this article on Virtual Multiblogs](http://striderweb.com/nerdaphernalia/features/virtual-multiblog/) which is based on a similar approach. It looks like he used a bunch of different directories all mapped to one WordPress directory. In the approach I’ve taken, I point everything to the same directory (in the HTTP virtualhost section), and simply rely on the HTTP\_HOST/SERVER\_NAME that’s sent with each request. In addition, I’m doing the wp-content remapping, which I don’t think was done with the Virtual Multiblogs approach. I also did a bit more, as you’ll see next.

You might be asking why I didn’t just do away with the wp-content directory although, and simply remap it to wp\_domains/duanestorey.com — it’s a good question.

There are a few problems with the current approach. First, remapping the wp-content directory often breaks plugins because many plugin developers hardcode the URLs as /wp-content in the code (it’s sloppy, but I’ve done it previously as well). So as soon as you move your wp-content directory, all those plugins start showing errors. Second, some javascript libraries and statistics programs (the popular Mint program, for example) must be installed in the root of the website. With this model, you really wouldn’t want users to mess with any core WordPress files or directories, so they really shouldn’t have access to the root installation. So what do we do?

Well, I came up with a little .htaccess hack such that anything that isn’t found in the core WordPress path is automatically redirected to the domains/WEBSITE\_NAME directory. That means if http://www.migratorynerd.com/test.html isn’t found on disk in wp\_install/test.html (which is the base WordPress installation, so it definitely won’t be there), the .htaccess redirect will send it to wp\_domains/WEBSITE\_NAME/test.html (or in my case, wp\_domains/duanestorey.com/test.html). The redirect is internal, which means that from the user’s perspective they are really looking at http://www.migratorynerd.com/test.html (but on disk they are actually viewing the file in wp\_domains/duanestorey.com/test.html).

This approach should fix most of the plugin problems since it uses URL remapping to trick the website into thinking everything actually is in the http://somedomain.com/wp-content directory, even though there’s nothing really there. It also should allow users to install whatever they want in their directory (mint, javascript, whatever), since anything not actually found in wp\_install will redirect to their site-specific domain files in wp\_domains.

In fact, once you do the .htaccess change, there’s no need to define WP\_CONTENT\_URL in wp-config.php anymore, since the htaccess will take care of all of that automagically. So, feel free to remove:

`define( 'WP_CONTENT_URL', 'http://' . $_SERVER["SERVER_NAME"] . '/domains/' . $website . '/wp-content' );`

Internally, http://www.migratorynerd.com/wp-content/themes/duane-apr09/style.css is actually mapping to http://www.migratorynerd.com/domains/duanestorey.com/wp-content/themes/duane-apr09/style.css, but since .htaccess is hiding all of that, plugins with hardcoded elements should work normally.

I’ve currently moved this website over to this new approach, and will probably move a few more tomorrow and test it out further. Ultimately I can stop backing up whole directories with WordPress installations, and simply backup my wp\_domains directory nightly. So far, things appear to be working quite smoothly (even old images that were inserted into posts using relative /wp-content/ URLs work properly thanks to the .htaccess trick).

The current structure of my wp\_domains directory is:

- /duanestorey.com
- /duanestorey.com/mint/\*
- /duanestorey.com/wp-content/\*
- /duanestorey.com/db.config

The .htaccess hack I came up with is below:

`# BEGIN WordPress<br></br><ifmodule mod_rewrite.c=""><br></br>RewriteEngine On<br></br>RewriteBase /</ifmodule>`

RewriteCond %{REQUEST\_FILENAME} !-f  
RewriteCond %{REQUEST\_URI} !(.\*)/$  
RewriteRule ^(.\*)$ http://%{HTTP\_HOST}/$1/ \[L,R=301\]

RewriteCond %{REQUEST\_FILENAME} !-f  
RewriteCond %{REQUEST\_FILENAME} !-d  
RewriteRule ^(.\*)$ domains/%{HTTP\_HOST}/$1 \[L\]

RewriteCond %{REQUEST\_FILENAME} !-f  
RewriteCond %{REQUEST\_FILENAME} !-d  
RewriteRule . /index.php \[L\]

\# END WordPress

I’m by no means a .htaccess expert, so if you see anything wrong with it, please let me know. It seems to be working though (I have Mint working).