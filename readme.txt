=== BlazeMeter ===
Contributors: nofearinc, BlazeMeter
Donate link: http://devwp.eu/
Tags: WordPress plugin, Load testing, JMeter, Functional Testing, Apache JMeter, Stress testing, Performance Testing, Website load testing, Webapp load testing, Mobile app load testing, BlazeMeter, JMeter Load Testing, Load Testing plugin, Load testing module
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The BlazeMeter module provides WordPress users a way to seamlessly load test their web or mobile site or application for performance.

== Description ==

**BlazeMeter WordPress Module for Load and Performance Testing**

* Create a realistic load test of thousands of virtual users in less than 10 minutes.
* No scripting is required.
* You don’t need to install software or configure hardware.
* Test your authenticated website section as well as your anonymous website section.
* Load traffic can originate from numerous locations worldwide.
* Easily evaluate system performance and run numerous iterations, allowing users to locate bugs and bottlenecks, fix and re-test time and time again.

Use next generation cloud testing tools without having to master scripting or having to be a performance expert.

http://www.youtube.com/watch?v=Uo7NUSFtWTY

= Features =
**Phenomenal Features**

* A Cluster of Dedicated JMeter™ Resources
A pre-configured JMeter™ environment for simplified load testing with up to 144 CPU cores and 500 GB of memory! A JMeter Supercomputer, at your service.

* Virtual Centralized Console
Easily set up tests, access test results, view test reports, compare past test reports all on a unitary console. Easily access or share any data between teams. Think of it as Google Drive for load testing.

* Test Behind the Firewall
Use your VPN credentials to integrate a series of our dedicated load servers into your private network.

* Developer Tools
Easily integrate into your own environment using our existing plugin library (Jenkins, Drupal or JMeter for example) or use our API to connect to any system.

* Realistic Testing & Scripting
Each fully simulated (browser) user can simulate any browser, unique username/password, cache, cookies, parameters extraction, sign up, sign in, credit card transactions and other scenarios. All supported up to a scale of 300,000 concurrent users!

* Master/Slave Reporting
Test your website and aggregate multi-geo locations results into a single report OR run numerous tests, each executing a different script from a different geo-location and then aggregate results into a single report.

* Extra-Large Testing Capacity
Launch a single dedicated server or a cluster of 60. Test 100, 1000 or up to 300,000 fully simulated browser users!

* Google Analytics Integration
Retrieve relevant site data from your Google Analytics account and integrate automatically into a new test setup.

* Comprehensive Protocol Support
Stress test your SQL systems (MSSQL, MySQL, Oracle), HTTP/S, Web services, Mobile and TCP/IP.

* Smart Functional Testing
Analysis of .JTL files to assess errors or failed transactions of any tests run using a JMeter script to ensure assertions are met.

* Multi-Geo Locations
Set the geo location for traffic origin of choice. Our servers are located in California, Oregon and Virginia USA, Tokyo, Ireland, Brazil, Singapore and Australia.

* Test Report Archive
Review all current and past test results directly from the test management system.

* Ease of Use
Start testing in under 5 minutes, no prior scripting knowledge needed! Simply upload your scripts or we'll run automatic scripting for you.

= How to use the WordPress Module =

Go to the BlazeMeter admin page. In the admin section you will find three sections:

1. Anonymous users
2. Authenticated users
3. Configuration

**Anonymous access section**
Specify the maximum amount of concurrent **anonymous** users that will be simulated in the load test. You can further specify the list of pages, each anonymous user will access one after the other.

**Authenticated users section**
Not only you can specify the maximum amount of concurrent **authenticated** users that will be simulated in the load test and the list of pages they will access, you can also specify the number of **unique authenticated users**. This will cause the module to actually create these users with unique credentials in WordPress. The newly created users will be used for the load test.

**Configuration**
The configuration section includes a few fields that will help build the test and get it ready to run.

*Load Scenario*
You have the option to select one of three pre-defined ‘best practice’ load scenarios.

*Domain*
State the public domain name of your website. Please note that **this doesn’t need to be a real domain**. This is very important. It needs to be the domain name that your WordPress is expecting to see and is configured to use.

*IP*
State the public IP of your website.

*UserKey*
Add the user key that identifies your account at BlazeMeter. This can be found under your BlazeMeter account settings.

*Test ID*
Read only. The Test ID is automatically set by BlazeMeter.

*Test Name*
Add the name of the test as you want it to appear in your BlazeMeter account.

**Signup for a free account or log in with your existing credentials to get your user key and get your test going.**

**You must have a valid account and a valid user key. Free or paid accounts are both acceptable.**

= What to expect =

Once you’ve run the test,  a group of anonymous users will visit your website, going through the list of pages specified in the anonymous list of pages. In parallel, a group of authenticated users will be visiting your website going through the list of pages specified in the authenticated list of pages. These user credentials will become a part of the list of newly created authenticated users.

The BlazeMeter module, having an insight into your WordPress installation, generates an Apache JMeter and a Selenium script for you automatically. It then provisions and configures a cluster of up to 100 dedicated servers ready to run on demand. All this is done automatically, saving you days of scripting, provisioning and configuring.

BlazeMeter is a load testing cloud fully compatible with Apache JMeter. BlazeMeter can generate realistic traffic according to any provided JMeter script. With the BlazeMeter module, you do not need to create any script. All scripts are created for you automatically and fit your WordPress installation perfectly.

The BlazeMeter module will create a JMeter load script that will simulate both anonymous users and authenticated users visiting the WordPress website. During a load test, a dedicated cluster of load engines is launched in a preconfigured geographic location. These servers generate traffic according to the JMeter script generated by the BlazeMeter module according to the parameters set by the user. During the load, real time measurements of KPIs present themselves on the report dashboard, where users can easily evaluate system performance and run numerous iterations, allowing users to locate bugs and bottlenecks, fix and re-test time and time again.

== Installation ==

**Install via Search:**

1. In WordPress admin, visit Plugins > Add New
2. Search for "blazemeter"
3. Click the "Install Now" link and click "Ok" (if necessary) in the pop-up dialog.
4. Once installed, click the "Activate Plugin" link.

**Install via Upload:**

1. In WordPress admin, visit "Plugins" > "Add New" > "Upload"
2. Upload blazemeter.zip file
3. Once uploaded, click the "Activate Plugin" link

== Frequently Asked Questions ==

= How do I get started? =

After installing the plugin simply follow the instructions in the plugin to obtain an API key - 

= How do I get support? =

Easy, simply [reach out](mailto:support@blazemeter.com) to us and let us know how we can help!

= I want a feature that you have not included, can you add it? =

We take feedback requests very seriously. [Drop us a note](mailto:support@blazemeter.com) or [leave a suggestion](http://community.blazemeter.com/forums/139158-help-us-improve-blazemeter?query=%2Cx%20cs.d%2C%20scASCvswfc) in the community. Be sure to check back for updates on new and upcoming features, your suggestion could be next!

== Screenshots ==

1. BlazeMeter Admin Options
2. Autosuggest for pages
3. Load test graphic
4. Slider toggles
5. Test report
6. Errors while testing
7. Load results graph

== Changelog ==

= 1.0 =
* Initial release
