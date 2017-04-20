<?php

# Hide Empty Sections MediaWiki Extension
# Created by Vivek R. Shivaprabhu (vivekrs.rsv@gmail.com)

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

global $wgHooks;
global $wgExtensionCredits;

$wgExtensionCredits['parserhook'][] = array(
	'name' => 'Hide Empty Sections',
	'type' => 'hook',
	'author' => 'Vivek R. Shivaprabhu (vivekrs.rsv@gmail.com)',
	'version' => '1.0',
	'update' => '03-05-2009',
        'url' => 'http://www.mediawiki.org/wiki/Extension:Hide_Empty_Sections',
	'description' => 'Hide sections that do not have any text in it.',
);

$wgHooks['ParserAfterStrip'][] = 'fnHideEmptySections';

function fnHideEmptySections( &$parser, &$text, &$strip_state ) {
	global $action; // Access the global "action" variable
	// Only do the replacement if the action is not edit or history
	if(
		$action !== 'edit'
		&& $action !== 'history'
		&& $action !== 'delete'
		&& $action !== 'watch'
		&& strpos( $parser->mTitle->mPrefixedText, 'Special:' ) === false
		&& $parser->mTitle->mNamespace !== 8
	)
	{
		$comment_pattern = '/<!--(.|\n)+?-->/';
		$text = preg_replace ($comment_pattern, '', $text);

		$pattern[] = '/([^=])(====[^=]+?====\s*)+(={2,4}[^=]|$)/';
		$pattern[] = '/([^=])(===[^=]+?===\s*)+(={2,3}[^=]|$)/';
		$pattern[] = '/([^=])(==[^=]+?==\s*)+(==[^=]|$)/';
		$replace = '\1\3';
		$text = trim ( preg_replace ($pattern, $replace, ' ' . $text) );
	}

	while ( preg_match ('/\n\s*\n\s*\n/', $text ) )
	{
		$text = preg_replace ( '/\n\s*\n\s*\n/', "\n\n", $text );
	}

	return true;
}
