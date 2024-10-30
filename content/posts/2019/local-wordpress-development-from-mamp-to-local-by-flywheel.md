---
title: "Local WordPress Development: From MAMP to Local by Flywheel"
date: "2019-11-19T17:16:16.000Z"
categories: 
  - "code"
coverImage: "desk-laptop-phone-coffee-scaled.jpg"
---

Years ago, when looking for a way to do local WordPress development, I eventually stumbled upon a pretty nifty tool called MAMP.  MAMP stands  for "Mac/Apache/MySQL/PHP", and it's the Macintosh equivalent of the well-known Linux-based LAMP stack.

While you can configure MAMP by editing configuration files, I decided to upgrade to MAMP Pro, as it gives you an easier UI to use when managing some of your local websites.

Despite MAMP 'mostly working', it has a number of really annoying downsides as well:

- MAMP Pro is paid software, but despite buying it multiple times, I don't really feel like I've gotten good value with upgrades. Whenever I visit the website, it seems like there's a new version that I have to upgrade to in order to even get a new PHP version etc.
- It's very unstable: often it will seize up completely and you'll have to force-quit it. This is a known issue that has never been resolved
- Adding SSL is a manual process  - that may have been fine a few  years ago, but SSL is mainstream now

Recently I set out on a quest to completely get rid of MAMP, and asked around with some of the developers I know who routinely develop themes and plugins for WordPress on their local machines. The two most popular options were Vagrant and 'Local by Flywheel'. I investigated them both, but eventually decided to give Local a try.

### Using Local by Flywheel

Local by Flywheel, similar to Vagrant, uses virtualization technology.  What that means is your websites basically run inside of mini-computers, and those mini-computers run on your real one.  The benefit to this is that each website is completely isolated from the others, and can easily run entirely different web-server and PHP stacks.

Going even further, Local by Flywheel uses Docker, which is an even lighter-weight form of virtualization done primarily by exploiting resource containerization in the operation system. That means spinning up a new website (or a Docker container) is very fast, and can be configured to only use a certain amount of CPU and memory resources - this is a huge plus compared to MAMP.

### Adding a New Site on Local

Compared to MAMP, adding a new site on Local is a breeze. First, open the application, then click the + sign to add a new site.  First, you'll need to put in a name for the site.

One aspect I really like is that it takes the name and automatically appends .local to it.  With MAMP I ended up with a lot of different strange domain names that I had to remember. While you could change it in Local, the hint it gives you with .local is great, and I simply have to scan the browser name to see if I'm live or local.

You can customize what type of environment you want for the site in terms of web server (Apache or nginx), PHP version, and MySQL version.  The default is the latest version of PHP with nginx and MySQL.  But if for some reason you need to vary that, you can.

Another amazing feature of Local is that it will automatically create the WordPress installation as well, complete with database. This is a HUGE time saver, and once you get used to it, you won't want to ever have to install WordPress locally again. Since there's only ever one MySQL database on your site (since the site has it's own container), you don't even need to care about the database name or password - I just leave wp-config.php alone when I import a new site.

After the site is set up, you can configure it further via the dashboard. If at any point in time you need to change the web server, PHP or MySQL, you can easily do it here. Once the site is provisioned, simply click the "View Site" button and it will take you to you new site.

To provision a new site honestly takes about 15 seconds thanks to Docker and how Local is integrating with it. Each site also can only use a limited set of resources as well, so you don't need to worry about your machine bogging down if you accidentally do an infinite loop.

### Using Local

You can simply turn on or off sites at leisure, which under the hood just restarts the Docker instance I imagine. But compared to MAMP, Local is so much snappier and more responsive, and I've been amazed and how smooth my workflow is now with regards to local development.

One caveat though - because these are small containers (light-weight virtual machines), you cannot access any files outside of the container.  This immediately caused me issues because my primary code repository is located outside of the Local Sites directory, and I routinely work on code that is tied to that repository.

The good news though is there's a solution - Local's '[Volume Add-on](https://github.com/getflywheel/local-addon-volumes)'. Using that add-on you can map a directory outside of the virtual machine to one that's inside. I now use that to map both WordPress themes and WordPress plugins into my Local containers for active development.

### Final Thoughts

I was definitely late to the 'get rid of MAMP' party, but now that I'm here I'm sad I waited so long.  Honestly, I don't know how anyone can recommend MAMP Pro anymore, especially considering how many great free options like Local by Flywheel there are.  As soon as they release their Local Pro version, I'll likely send some money their way as it's a great piece of software.

If you're looking to try Local by Flywheel, head on over to their site and [download a copy now](https://local.getflywheel.com/).

_P.S. this post was written in Gutenberg :)_
