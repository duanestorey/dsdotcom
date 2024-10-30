---
title: "How To Beat Time Or Bandwidth Limited Internet Connections"
date: "2013-12-04T03:21:26.000Z"
categories: 
  - "travel"
tags: 
  - "internet"
  - "mac"
  - "network"
coverImage: "code.jpg"
---

One of the hardest parts of working remotely and traveling is the need to find reliable internet from time to time. In some countries free internet is almost as ubiquitous as beer, and often advertised as prominently. But in some countries (I'm looking directly at you Australia and New Zealand) almost all the internet you will find in the wild is either time limited or bandwidth limited. It's not unusual to be in a coffee shop on only be given 10MB or so, which is hardly enough to check your email or Facebook feed.

### The Solution

If you're using a Mac computer (and I'm sure the procedure is fairly similar on Linux) and have root access, you can easily work around these types of internet connections by changing your network adapter's MAC address. Since these services usually tie the time or bandwidth consumption to your computer's MAC address (which is really the only way to identify a particular piece of hardware), most of the time a new MAC address will trick the system into thinking there is a brand new user on the system.

The command to switch to a randomly generated MAC address is as follows:

`` openssl rand -hex 1 | tr '[:lower:]' '[:upper:]' | xargs echo "obase=2;ibase=16;" | bc | cut -c1-6 | sed 's/$/00/' | xargs echo "obase=16;ibase=2;" | bc | sed "s/$/:$(openssl rand -hex 5 | sed 's/\(..\)/\1:/g; s/.$//' | tr '[:lower:]' '[:upper:]')/" | xargs sudo ifconfig en0 ether`  It's probably a good idea to write down your old MAC address as well (you can execute 'ifconfig' from Terminal to find it) so that you can always set it back to the old setting once you are done. But if you find yourself using a public internet terminal that is time or bandwidth limited, simply switch your MAC address when your session expires and you should be good to go all over again.  I've tried this a few different times now in a few different countries, and it works flawlessly - as soon as you hit your time limit at a coffee shop or airport, just change your MAC address and you should be recognized as a new user.  ### Play Nice  Also remember that in some parts of the world people actually pay for the bandwidth they use, so bear that in mind if you use that trick. I see no harm in using this occasionally if you are just checking email, or in a some huge place with a big budget like an airport. But it's probably not a good idea to use this trick excessively, just in case some small business owner gets hit with a bill long after you are gone.` ``
