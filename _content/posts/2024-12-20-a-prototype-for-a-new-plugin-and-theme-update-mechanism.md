---
title: "Juniper: A New Update Mechanism For WordPress Add-ons"
publishDate: "2024-12-20"
slug: "a-prototype-for-a-new-plugin-and-theme-update-mechanism"
category: 
  - "wordpress"
tag:
  - "plugins"
heroImage: "_images/2024-gin.jpeg"
---

As most people in the community know, over the last few months things have pretty much gone to shit in WordPress-land. 

Every time you think it can't get any worse, it somehow manages to. WP Engine won their preliminary injunction against Automattic and Matt Mullenweg, which meant that about a week ago, the status quo was mostly put back in place - that meant, amongst other things, the Advanced Custom Fields plugin was returned to its rightful owner, WP Engine, and the loyalty checkbox on WordPress.org was removed (and then strangely replaced with a pineapple on pizza pledge box instead). Most of us in the community held our breath that things would get back to normal at that point, but whatever peace we had unfortunately wasn't lasting.

Last night a new [post went up on WordPress.org](https://wordpress.org/news/2024/12/holiday-break/), basically saying no new plugins or theme submissions will be taken during the holidays, or any new user registrations. The text officially calls these actions a 'Holiday Break', but when things would return to normal was unfortunately left open-ended:

>As you may have heard, I’m legally compelled to provide free labor and services to WP Engine thanks to the success of their expensive lawyers, so in order to avoid bothering the court I will say that none of the above applies to WP Engine, so if they need to bypass any of the above please just have your high-priced attorneys talk to my high-priced attorneys and we’ll arrange access, or just reach out directly to me on Slack and I’ll fix things for you.

>I hope to find the time, energy, and money to reopen all of this sometime in the new year. Right now much of the time I would spend making WordPress better is being taken up defending against WP Engine’s legal attacks. Their attacks are against Automattic, but also me individually as the owner of WordPress.org, which means if they win I can be personally liable for millions of dollars of damages.

Even though Matt was the one who went up on stage at WCEU and essentially picked a fight with WP Engine, he's still trying to play the victim card in all of this, which is just sad.  He's the one who went with the full on frontal-assault with WP Engine, and he's the one who escalated it by taking ownership of the Advanced Custom Fields plugin, something which is, as far as I'm aware, unprecedented in the open-source world.  I'm no lawyer, but I'm pretty sure when a judge rules against you and issues a preliminary injunction essentially from the bench due to your actions, it pretty much means you weren't attacked, but rather were the attacker. 

Matt was actually one of the individuals who first inspired me to contribute to WordPress.  Him and I first met at a [conference in Vancouver called "Northern Voice"](https://ma.tt/2008/02/northern-voice/) in 2008.  A mutual friend of ours, Boris Mann (who I met during my high tech days in Ottawa when I worked at JDS Uniphase in 2000-2002 as a manufacturing engineer), introduced us after Matt's talk.  After that, I doubled down on WordPress, and helped create one of the first mobile plugins for WordPress, WPtouch, along with a smattering of others. Two of the plugins Dale Mugford and myself created, WPtouch and WordTwit, [were voted the #1 plugins for WordPress by users at WordCamp San Francisco in 2009](https://youtube.com/clip/UgkxLBxSsGgjzdGQnVdL7tDGp_tQHsyACDvH?si=ycfB7f2k0ivKks_f). I also helped organize three WordCamps in the Vancouver area during my time there. 

That's why it's been so painful watching what's occurred the last few months, where basically the steward of the open-source WordPress project has basically weaponized a huge chunk of it and shown most of us, in my opinion, that not only are our opinions not valued, but our contributions to WordPress, however large or small, aren't really valued.  

### The Way Forward

This morning long-time WordPress plugin author and overall WordPress proponent Joost de Valk [posted a thoughtful piece about WordPress, and what we need to do break the status quo](https://joost.blog/wordpress-leadership/). I've been somewhat saddened publicly by the lack of trustworthy voices in the community that have been willing to stand up and say what they really feel or publicly condemn Matt's actions, so I'm thankful that someone of Joost's status has finally stood up and said it's time for a concrete change. If the WordPress community were an eco-system, then guys Joost would be the starfish in it, the keystone species that have long helped keep it all together.  

What's clear from Joost's post, as well as my own private musings these last four months, is that WordPress.org can no longer be allowed to be weaponized against the community.  Plugin and theme authors shouldn't fear that their plugins will be taken over, nor should people have to endure hardship when it's taken offline, which is what is happening now for the holidays. Don't get me wrong, people need and deserve a break.  But WordPress.org is, in my opinion, clearly being used at this point as a means of controlling not only the competitive interests of Automattic, as was the case in locking WP Engine out of it, but also potentially as a lever against the community itself.

With that in mind, it's time to start working on plans to break that iron grip.  If the community is to survive, then new systems need to emerge to facilitate that.  I don't believe a full-fork is necessary, or even desired at this point.  But what is desperately needed is a way to minimize the role that WordPress.org itself has on the community project known as WordPress, certainly when it comes to the distribution of plugins and themes.

### A New Method For Plugins And Themes

I've spent the last few months thinking in my head about alternate ways to update plugins and themes. There are already some great projects like [GitUpdater](https://git-updater.com/) and [RepoMan](https://github.com/littlebizzy/repoman) that many authors are already using.  I also know for a fact there are some exciting new projects currently being developed, and I suspect that we will see a few of those emerge in the next few weeks or months.  But as for me, I have some very particular ideas about what a new plugin or theme repository should look like.

First, it should not be a traditional repository. It should take advantage of as many pieces of existing infrastructure as possible without the need to re-invent the wheel entirely, with no ability to basically shut it down at will.

Second, it needs to provide a curated list of plugins and themes in the WordPress admin so people can explore and install plugin or themes from there.  It also needs to provide a means of ranking those plugins and themes, one that doesn't include a commercial entity gaming the list. 

Third, it needs to allow for the ability to verify that an update is legitimate - that is, that the update came from the original author, and wasn't simply taken over as was the case of ACF.  That can be accomplished via cryptographic means.

And lastly, it needs to provide a life-raft for plugin or theme authors looking to help get their plugins and themes off WordPress.org. I've given this lots of thought, and I think a simple addition of a Header to a plugin or theme on WordPress.org should suffice when the time comes.  If they disallow this, I have already thought of work-arounds.  But there needs to be a way to say, "hey, I'm done with WordPress.org" and have the majority of users easily move to a new repository without a ton of friction or work.

With that in mind, I'm going to go on a marathon coding session for the next 48 hours to try and hack out a proof of concept.  I think in that time I'll be able to hit the big pieces of that.  

### What Happens Next

My goal with what I'm about to build is to hopefully provide a proof of concept to generate some ideas.  If it doesn't stick, or a replacement comes around right away that the community rallies behind, that's great.  Before Matt got on stage at WCUE and started this battle, I was happily eating paella on the Valencian coast in Spain. I've only performed my Ali-like return to the ring due to his actions, and my desire to help the community survive his assaults.  I'm quite happy to help generate some new ideas, and to work tireless to help get the community back to a safe position, and then return to my paella-fuelled life. 

I no longer have any commercial plugins, nor any real skin in the game with WordPress. I've spent most of the last year playing with hardware, not software.  What I do have is a 15 year history with a community that I have come to love and appreciate, one that is being fractured by the very person who we all thought was supposed to protect it.  If and when proper governance emerges, I'm happy to turn over whatever I write to them, which isn't really necessary since it's all GPL anyways.  But based on what I'm trying to achieve, once it's released it no longer depends on me at all, since it'll effectively propagate itself anyways. 

### Want To Help?

I'm going to be coding mostly in real-time this weekend.  I've set up three repositories over at [NotWPorg](https://github.com/notwporg) on Github.  There are three pieces to this in my head.  Because all projects need a name, and I'm likely to have a bit of Gin by the fire while coding this weekend, I've affectionately called this 'Project Juniper'

1) Juniper/Author

This is going to be a plugin that plugin and theme authors can install on their WordPress site.  Here they can manage their own GitHub plugins and create releases.  Creating a release will pull directly from the GitHub 'Releases' information, but also allow the author to cryptographically sign the ZIP file, helping prove that a) they created the ZIP and b) it wasn't tampered with during transport and prior to install.  Once it's signed, the plugin updater (discussed later) will refuse to install any plugin that fails a signature verification.  It will also provide an XML API for the next piece:

2) Juniper/Server

This is going to be a very light-weight, mostly statically generated, self-mirroring plugin repository. Here you'll find a curated list of all plugins by all users of Juniper/Author. For now, plugins will be self-submitted to the repository, but I may change that so Juniper/Author automatically facilitates an update.  The important point here is that a mirroring process will be baked into it.  My plan is so anyone can fork Juniper/Server, change a few config lines, and stand up a completely independent mirror of the plugin information in a few minutes, on something as simple as a Digital Ocean droplet. That information will be used by:

3) Juniper/Berry

This piece has the coveted Juniper "Berry" designator, which is our favourite flavouring addition to Gin. Juniper/Berry will pull from a list of multiple Juniper/Server instances, or even private ones if desired. Like DNS, I'll try and make it automatically failover, so if one or more of these disappear, things will proceed as normal.  

It may be possible to ditch Juniper/Server at some point, but at this point I think it's necessary evil and will offload some of the larger data processing away from the client and onto the server to create the master lists.  I plan to use a page-rank type algorithm incorporating Github stars, issues, and a new method I'm considering involving a new plugin and theme header, "Endorses:".  This is basically a way for plugin and theme authors to endorse other plugin and theme authors, helping propagate trust through the eco-system.  This isn't an admission that a plugin or theme doesn't have security issues in their code, or that their plugin or theme won't break your site.  But it is an endorsement that this plugin or theme author generally does good work and likely has created something useful, enough so that it should be given a slightly higher weight in a curated list.

### Let's Fucking Go

![LFG](_images/lets-fucking-go.jpeg)

In the words of Wolverine, "Let's Fucking Go". 

If anyone wants to follow along, I'll be updating all three repos over the next 48 hours in mostly real-time.  Other than stopping to tend the fire here in Canada, or to pet my sister's beagle, I'm going to see how far I can get in 48 hours.  I'm neither an expert in cryptography or semi-distributed systems.  So if you're following along and see something that can be improved, please let me know.  Like I said, this is just a proof of concept, but like all long distance journeys, they start with a single, solitary step.

[Robert DeVore](https://x.com/deviorobert) has already gracious offered to help with a bit of testing, but if anyone else has a bit of free time this weekend, I can use some help with testing from the following three groups:

1) People who don't mind standing up a bare-metal PHP instance to run a mirrored copy of Juniper/Server
2) People who have plugins or themes exclusively on GitHub that want to be a part of the initial curated list of plugins
3) People who have a plugin or theme dual-released on WordPress.org and Github, who want to help test the life-raft functionality.

Just to be clear, nobody will be forcibly moved from WordPress.org to a new repository. Whatever I come up with will involve the website owner having informed consent that a new, signed version exists, and will require them to voluntarily agree to move to the new repository. That's a lot more than WP Engine's users were given, and the bare minimum that should be required to change code on someone's server.

As always, you can find me over on [X](https://x.com/duanestorey) or [Bluesky)(https://bsky.app/profile/duanestorey.com) - hit me up there to have timely responses to any of this.  

And on that note, it's time to code.


