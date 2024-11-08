---
title: "Connectors And Interconnects"
publishDate: "2023-01-28"
modifiedDate: "2023-01-28"
slug: "connectors-and-interconnects"
author: "Duane Storey"
featuredImage: "_images/connectors-and-interconnects-featured.jpeg"
---

If you look at most photos of do it yourself (DIY) audio amplifiers, they almost always use screw terminals to connect everything together (and rarely have any sort of digital component – they are almost always two channel audio systems). While using screw terminals is pretty easy as a do-it-yourself’er, to me it seemed a bit inelegant.

For example, if you open a commercial amplifier, you’re almost always going to see a bunch of neat interconnects between components. So I knew from the start I wanted to have everything use connectors if possible to make assembly, and future disassembly for updates/improvements, easier.

#### Low Power DC Connectors

For the low power DC connections and audio signal connections, I chose to use JST-PH connectors. They have a 2.0mm pitch, are readily available, and can handle up to 100 Volts and 2 Amps.

#### High Power DC Connectors

For the higher power DC connections, namely the power/grounds to and from the power amplifier boards and the speaker terminals, I chose to use JST-VH connectors. They come in a 3.96mm pitch, and can handle up to 250 Volts and 10 Amps.

#### Low Power AC Connectors

There are two AC connectors internally to the digital power supply and analog power supply boards. As these are PCBs, I chose to use JST-XH connectors, and connector that comes in a 2.50mm pitch and can handle up to 250 Volts and 3 Amps.

*In the future I’ll likely modify the PCBs to use JST-VH for these connections to simplify things, but the boards I created use JST-XH*

#### Higher Power AC Connectors

For the connections to and from the main power transformer, I originally used JST-EL connectors, which are 4.5mm and are meant for wire-to-wire high power connections. The main power transformer I used is 300 VA, so the primary winding should average less than 1.3 Amps, but the secondary windings at 20V can output up to 7.5 A, so the connectors needed to handle this current.

*I’m in the process of modifying this to likely use screw terminals, as having to splice connectors onto a commercial transformer, simply to connect it to a PCB, seems to be a bit overkill. As the transformer is one component that is unlikely to change often, I can handle using terminals here. JST-EL isn’t super common either, so it will be one less item to worry about purchasing.*