#!/usr/bin/php

Info: http://github.com/mathiasverraes/speakingjenkins/

Required:
	--url=http://YOURJENKINS/job/YOURJOB/
Optional:
	--username=NAME
	--password=PW

To run in background, add "> /dev/null &"

<?php
use SpeakingJenkins\Speaker\Speaker;

// Configuration
define('INTERVAL', 60); // in seconds

require_once __DIR__.'/../lib/SpeakingJenkins/Speaker/'.PHP_OS.'Speaker.php';
$speakerClass = '\SpeakingJenkins\Speaker\\'.PHP_OS.'Speaker';
$speaker = new $speakerClass;

// command line options
$url = ''; $username = ''; $password = '';
$options = getopt('', array('url:', 'username::', 'password::'));
extract($options);
if(empty($url)) {
	die('Please pass the URL to your Jenkins job'.PHP_EOL);
}

// authentication
$context = array();
if($username && $password)
{
	$context = array('http' => array(
		'header'  => "Authorization: Basic " . base64_encode("$username:$password")
	));
}
$context = stream_context_create($context);

// get job info
$job = json_decode(file_get_contents($url.'api/json', null, $context));
$current = '';

// main loop
while(true)
{
	$last = file_get_contents($url.'api/xml?xpath=//lastCompletedBuild/url/text()', null, $context);
	if($current != $last)
	{
		$build = json_decode(file_get_contents($last.'api/json', null, $context));
		echo sprintf("%s %s %s", $build->fullDisplayName, $build->result, $build->id).PHP_EOL;
		$current = $last;
		if($build->result !== 'SUCCESS') {
			speak($speaker, $job, $build);
		}
	}
	sleep(INTERVAL);
}

function speak(Speaker $speaker, $job, $build)
{
	$speaker->speak(sprintf("job, %s, number %s, %s,", $job->displayName, $build->number, $build->result));
	foreach($build->culprits as $culprit)
	{
		$fullName = preg_replace('/<(.*)>/', '', $culprit->fullName); // remove email
		$speaker->speak(sprintf("culprit, %s,", $fullName));
	}
}

