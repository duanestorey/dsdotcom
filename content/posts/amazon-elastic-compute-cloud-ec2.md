---
title: "Amazon Elastic Compute Cloud (EC2)"
date: "2008-05-07T02:02:21.000Z"
categories: 
  - "journal"
tags: 
  - "amazon-ec2"
  - "co-location-3"
  - "s3"
  - "vancouver"
---

The Amazon EC2 service is a rather revolutionary cloud computing system that came out in late 2006. It provides a fairly flexible infrastructure that allows you to launch application servers on demand. Traditionally new web companies are forced to pay for rather expensive co-location facilities for their servers, with little or not thought given to scalability (which isn't always a bad thing, considering most companies never reach a point where they need scalability). That being said, for a company that either requires scalability, or is looking for new ways to reduce costs, Amazon EC2 might be a good fit.

Unfortunately, since every EC2 instance is essentially a virtualized computer, all data in an instance is lost should the instance suddenly terminate. There are several ways around this, such as storing instances and data over on Amazon's S3 service, but unfortunately none of them are entirely straightforward (especially if you're looking for MySQL or database persistence).

I recently discovered that one of our servers at work that is being kept in a local co-location facility was responsible for a fairly high level of traffic last month. Due to some crazy pricing schemes in the co-location facility, we were fairly heavily charged. I did a quick back-of-the-envelope calculation based on the bandwidth and usage patterns and quickly realized that Amazon EC2 would be about 8% of the cost that we were dinged.

I skimmed a few tutorials on the web and had a fully functional EC2 instance up in about 15 minutes. Assigning one of their new Elastic IP addresses was fairly trivial too. There are a few hiccups yet to work out due to the nature of the stuff I need to run on the box, but all in all it's been a fairly painless process. So while Amazon EC2 is still in beta and shouldn't really (IMO) be used for anything mission critical, in cases like the one I'm playing with it may be an extremely viable (and cost saving) option for some businesses.
