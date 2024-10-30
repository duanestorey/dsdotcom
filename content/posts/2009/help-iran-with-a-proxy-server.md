---
title: "Help Iran With A Proxy Server"
date: "2009-06-16T20:09:45.000Z"
categories: 
  - "journal"
tags: 
  - "ec2"
  - "iran"
  - "proxy"
---

<script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>

As many people know, there are protests going on in Iran, and the Iranian government is actively trying to stop the flow of information both in and out of the country. Twitter has been instrumental in helping getting the word out about what is going on there, so much so that they recently postponed a critical maintenance period simply so Iranian users could continue to use the service. A few hours ago I saw some requests on Twitter from Iranians asking for international proxies such that they could continue to use the Internet.

## Creating the AMI on EC2

Given that I have an EC2 account, and that setting up a proxy isn't too difficult, I decided to set one up. If you've never used EC2 before, this is a [great primer article](http://www.robertsosinski.com/2008/01/26/starting-amazon-ec2-with-mac-os-x/), so I'm not going to do an EC2 tutorial here.

First thing I did was to fire up a Fedora core image using my generated key-pair:

ec2-run-instances ami-225fba4b -k ec2-keypair

Then I logged into that instance as root so that I could configure the proxy server:

ssh -i ec2-keypair root@ec2-174-129-169-124.compute-1.amazonaws.com

You'll have to substitute your own instance identifier for the login information, which you can get using the command **ec2-describe-instances**.

Once inside, I went about editing the httpd.conf file in the /etc directory. I activated the proxy server and restricted access to Iranian IP addresses only, which I found on various lists on the Internet. The actual blob I added [can be found here](http://www.migratorynerd.com/wp-content/uploads/2009/06/proxy.rtf).

I then restarted Apache, and proceeded to test it using Safari on my local machine (I also added my public IP address to the Allow list). It seemed to work like a charm.

Next step is to allocate a public IP using EC2. This can be done using the following command:

ec2-allocate-address

You'll then get a public IP address. All that's left is to assign it to your running instance using a command like:

ec2-associate-address -i i-5d478934 some-ip-address

At that point you'll have a public web proxy on port 80 that will only allow proxy connections from Iranian IP addresses.

I did a quick Google and found a few people begging for an [AMI image to do this for Iranian users](http://developer.amazonwebservices.com/connect/thread.jspa?threadID=33059&tstart=0), so I've bundled one up and made it public. In theory you'll just have to launch a new instance based on this AMI, and assign a public IP address to it. Everything else should be taken care of. Remember to only distribute proxy IP addresses privately, as Iranian officials are blocking them as they find them.

The AMI that does this is ami-a37b9dca. If you have any problems, drop a comment.

Update - apparently I accidentally included my cert file in the AMI. I've since regenerated it, so it can't be used for anything. The AMI still works fine though, so feel free to use it.

## How does it work

Once you setup a proxy server and give the IP address to someone, they can simply set that IP address as their web proxy. For example, in Safari you set it in Network preferences:

![](images/Picture-14.png)

For everyone outbound request, the browser will contact the proxy instead and ask it to grab the actual information itself and forward it along. That way, if a service such as Twitter is blocked, the proxy will get the information from Twitter itself and forward it onto the client. So once you have a public IP address associated with your EC2 instance, simply get that into the hands of someone in Iran, and they'll be able to use your proxy server to access sites on the Internet that are blocked in Iran.
