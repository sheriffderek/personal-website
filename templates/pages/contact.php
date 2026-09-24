<?php
$brief = [
	'goal' => 'Zero friction between "I want to talk to this person" and talking. The '
		. 'booked call is the best door; email and the meetups are the softer ones. '
		. 'Nothing here should make you hesitate.',
];

/* The same facts as the prose below, said for search engines: a ContactPage
   about one Person. Built from the config constants the prose uses, so the
   email and call link can't drift between what people read and what
   crawlers read. */
$contact_data = [
	'@context' => 'https://schema.org',
	'@type' => 'ContactPage',
	'url' => SITE_URL . '/contact',
	'mainEntity' => [
		'@type' => 'Person',
		'name' => 'Derek Wood',
		'alternateName' => '@sheriffderek',
		'jobTitle' => 'Technical Product Designer',
		'url' => SITE_URL,
		'email' => 'mailto:' . CONTACT_EMAIL,
		'address' => [
			'@type' => 'PostalAddress',
			'addressLocality' => 'South Pasadena',
			'addressRegion' => 'CA',
			'addressCountry' => 'US',
		],
		'sameAs' => [
			'https://www.linkedin.com/in/derekthomaswood',
		],
		'contactPoint' => [
			'@type' => 'ContactPoint',
			'contactType' => 'Book a call',
			'url' => CALL_URL,
		],
	],
];
?>

<script type='application/ld+json'><?= json_encode($contact_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>

<text-content class='styled'>
	<h1 class='loud-voice'>Contact</h1>

	<p>You can email me at <a class='link' href='mailto:<?= CONTACT_EMAIL ?>'><?= CONTACT_EMAIL ?></a>.</p>

	<p>I’m @sheriffderek most places. My real name is Derek Wood.</p>

	<p>I live in South Pasadena (Los Angeles). You can find me at the LA Design and Dev meetup, maybe the JavaScript meetup, or come to my weekly open office hours.</p>

	<p>The fastest way to reach me is to grab a time: <a class='link' href='<?= CALL_URL ?>'>book a call</a>.</p>
</text-content>
