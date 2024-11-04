---
title: "How To Unfollow Ashton On Twitter"
publishDate: "2009-04-17"
modifiedDate: "2009-04-17"
slug: "how-to-unfollow-ashton-on-twitter"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "Ashton"
  - "Followers"
  - "twitter"
---

Ah yes, in what is probably one of the most documented penis measuring contests in recorded history, Ashton Kutcher has quickly vaulted his way up to 1,000,000 followers.

For whatever reason, Twitter seems to have disabled the unfollow/follow button his account, so people can’t unfollow him. Thankfully, the Twitter API has a solution to this problem. If you want to unfollow Ashton, try the following:

From a Linux or a Mac both (or something that supports curl), simply type the following:

curl -u **username**:**password** -d “” http://twitter.com/friendships/destroy/aplusk.xml

where **username** is replaced with your twitter username, and **password** is replaced with your twitter password. I can’t test it myself because I can’t seem to add him as a friend, but I think it should work.