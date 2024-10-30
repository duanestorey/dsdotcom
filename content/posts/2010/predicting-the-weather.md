---
title: "Predicting The Weather"
date: "2010-05-13T03:56:27.000Z"
categories: 
  - "journal"
tags: 
  - "forecasting"
  - "ubc"
  - "weather"
---

Most people probably don't know it about me, but I used to be involved in weather forecasting a long time ago. My first exposure to it was a co-op job I had in the summer of 1997 up in Whitehorse, YT. I was working for Environment Canada's weather centre, helping to develop JAVA programs for visualizing some of their data. JAVA was a new technology at the time, so it was essentially one big prototype. It worked decently enough, but I doubt it survived much past the end of my co-op term.

Years later I would be employed by the Department of Ocean Sciences at UBC as a part time software developer. I'm not entirely sure how I got the job to be honest, since I wasn't a computer science student. I guess they liked me during the interview process and had enough confidence that I wouldn't be a complete screw up. That job was my first exposure to developing software on a Linux platform, and I learned a lot of great skills there.

A lot of the code I wrote during that time is buried in the [UBC Weather Forecasting](http://weather.eos.ubc.ca/wxfcst/) pages. I actually got to write code that ran on a 256 node super computer, which was kind of cool. The majority of the images and movies on that page were (and judging by the looks, still are) generating using code I wrote. The actual output from the weather models are 5-dimensional data, which is a challenge to work with and to visualize. Since we only work in 3 dimensional data, most of the code I wrote attempts to slice the data in various dimensions and produce useful output.

At the heart of all weather models are a series of differential equations, mostly having to do with conservation of energy and momentum. For example, if you divide up Canada into a grid with a spacing of 10km, then the wind and solar energy going into each grid section has to equal the wind and solar energy going out of each section (obviously it's a lot more complicated than that). As such, the accuracy of weather models starts to increase as you include reflectivity of the ground, absorption of solar energy due to vegetation, topography effects etc (some of the work I did was updating all the topography data for some of the weather models).

Every night, weather stations around Canada and the US report their data to central repositories. Using that data, a particular weather model will be primed with the initial conditions of all those weather stations, and start working on solving all those differential equations. Back when I used to work there, it used to take anywhere between 30 minutes and 6 hours to finish (depending on the grid spacing). The end result was a forecast that predicted the weather conditions in North America for the next 5 days or so.

Unfortunately British Columbia is one of the hardest areas to predict the weather for due to its complex topography (which essentially leads to various microclimates around the province) and also what is known as the Pacific Data Void (since there are few active weather stations in the Pacific Ocean, there are essentially no data to prime the weather models over the ocean). To help improve the accuracy, the research team I was with implemented what they called ensemble weather forecasting. Basically you perturb all of the initial conditions slightly, and run various iterations of the model. What you end up with are multiple weather forecasts for the same period of time that all differ slightly. By combining all the data together in a probabilistic way, you get a more accurate prediction of what the weather will ultimately be. The work I did with that group actually got me a mention in my first [research paper](http://ams.confex.com/ams/pdfpapers/68296.pdf), although truthfully I didn't contribute much to any of the concepts in the paper other than implementing a pile of the code.

So next time you look at the weather forecast and wonder why it's off, now you know some of the reasons. And knowing is half the battle.
