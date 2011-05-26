Speaking Jenkins
================

Speaking Jenkins is a command line PHP app that announces the status of a Jenkins CI build using text-to-speech.

It works with Hudson as well, but you should probably switch to Jenkins anyway: 
http://stackoverflow.com/questions/4973981/how-to-choose-between-hudson-and-jenkins

Usage
-----

Run speakingjenkins.php from the command line.

```
Required:
	--url=http://YOURJENKINS/job/YOURJOB/
Optional:
	--username=NAME
	--password=PW
```

To run in background, add "> /dev/null &"


Todo
----

* linux support: http://linux.die.net/man/1/festival
* windows support
* multiple job support
* make interval configurable

... send in those pull requests :-)