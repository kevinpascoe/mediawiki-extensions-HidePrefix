<?php // HidePrefix.class.php //

/*
	------------------------------------------------------------------------------------------------
	HidePrefix, a MediaWiki extension for hiding prefix in links and page titles.
	Copyright (C) 2012 Van de Bugger.

	This program is free software: you can redistribute it and/or modify it under the terms
	of the GNU Affero General Public License as published by the Free Software Foundation,
	either version 3 of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
	without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU Affero General Public License for more details.

	You should have received a copy of the GNU Affero General Public License along with this
	program.  If not, see <https://www.gnu.org/licenses/>.
	------------------------------------------------------------------------------------------------
*/

if ( ! defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}; // if

class HidePrefix {


	// Hide prefix in links.
	static public function onLinkBegin( $skin, $target, &$text, &$customAttribs, &$query, &$options, &$ret ) {
		// Don't hide prefix in search results if option set
 		if($wgHidePrefixNotInSearch) {
                        $context = RequestContext::getMain();
                        if( $context->getTitle() == "Special:Search" ) {
                                return true;
                        }
                }
		if ( isset( $text ) ) {
			// Hmm... Sometimes `$text' is not a string but an object of class `Message'...
			if ( is_string( $text ) ) {
				$title = Title::newFromText( $text );
				if ( $title != null && $title->getPrefixedText() == $target->getPrefixedText() ) {
					$text = $target->getText();
				}; // if
			}; // if
		} else {
			$text = $target->getText();
		}; // if
		return true;
	} // function onLinkBegin


	// Hide prefix in page title.
	static function onBeforePageDisplay( &$out, &$sk ) {

		if ( ! $out->isArticle() ) {
			return true;
		}; // if
		$title = $out->getTitle();
		if ( ! $title instanceof Title ) {
			return true;
		}; // if

		// Don't hide the File Prefix on a page title if option is set
                if($wgHidePrefixDontHideFile) {
			if(strpos($title->getPrefixedText(), 'File:') !== false) {
				return true;
			}
		}

		if ( $out->getPageTitle() == $title->getPrefixedText() ) {
			$out->setPageTitle( $title->getText() );
		}; // if

		return true;

	} // function onBeforePageDisplay


} // class HidePrefix

// end of file //
