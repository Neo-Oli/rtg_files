<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Robert Gonda <robert.gonda@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   56: class tx_rtgfiles_pi1 extends tslib_pibase
 *   82:     function main( $content,$conf )
 *  135:     function download( $uid )
 *  193:     function getFiles()
 *  231:     function getSystems()
 *  264:     function sizeReadable( $size, $unit = null, $retstring = null, $si = true )
 *  302:     function parseTemplate( $subpart )
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */


require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Documents' for the 'rtg_files' extension.
 *
 * @author	rRobert Gonda <robert.gonda@gmail.com>
 * @package	TYPO3
 * @subpackage	tx_rtgfiles
 * @author_company	BSP Magnetica s.r.o.
 */
class tx_rtgfiles_pi1 extends tslib_pibase {

	var $prefixId = 'tx_rtgfiles_pi1';
	var $scriptRelPath = 'pi1/class.tx_rtgfiles_pi1.php';
	var $extKey = 'rtg_files';
	var $pi_checkCHash = TRUE;

	var $conf;
	var $GPvars;
	var $filesPath = 'uploads/tx_rtgfiles/';
	var $content = '';
	var $num_rows = 0;
	var $error = true;
	var $errorWord = '';
	var $wrapErrors = '|';
	var $valueWord = '';
	var $file;


	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The		content that is displayed on the website
	 */
	function main( $content,$conf ) {

		// Configuration
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$GLOBALS["TSFE"]->set_no_cache();
		$this->local_cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->GPvars = t3lib_div::GPvar( 'tx_rtgfiles_pi1' );

		// Configuration from TS
		$this->id = $GLOBALS['TSFE']->id;
		$this->pid = $this->conf['pidrootpage'] > 0 ? $this->conf['pidrootpage']: $this->id;
        $this->pid = $this->pi_getPidList( $this->pid, $this->conf['recursive'] );
		$this->totalTemplate = $this->cObj->fileResource( $this->conf['template'] );
		$this->wrapErrors = $this->conf['wrapErrorMessages'] ? $this->conf['wrapErrorMessages']: '|';

		// Download file by MIME type
		if( $this->GPvars['cmd'] == 'DOWNLOAD' && $this->GPvars['uid'] > 0 ) {
			$this->download( intval( $this->GPvars['uid'] ) );
		}

		// Form is submitted
		elseif( $_POST['submit'] ) {
			// Form data validation
			$this->GPvars['word'] = strip_tags( trim( $this->GPvars['word'] ) );
			if( strlen( $this->GPvars['word'] ) >= 2 ) {
				$this->valueWord = $this->GPvars['word'];
				$this->error = false;
			}
			else {
				$this->errorWord = $this->pi_getLL( 'error_word' );
				$this->error = true;
			}
		}
		$this->content = $this->parseTemplate( 'FILES_LIST' );

		// Debug information
		// error_reporting( E_ERROR | E_WARNING );
		// $listFiles = $this->getSystems();
		// $this->debug .= '<pre>GPvars:<br />'.print_r( $this->GPvars, true ).'</pre>';

		// Content rendering
		$content = $this->content /* .'<br />'.$this->debug */ ;
		return $this->pi_wrapInBaseClass( $content );
	}

	/**
	 * Download file
	 *
	 * @param	int		$uid:
	 * @return	void
	 */
	function download( $uid ) {

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery( 'uid,file,url,clicks', 'tx_rtgfiles_files', 'uid = '.$uid );
		if( $res ) {
			if( $this->file = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) ) {
				if( file_exists( $this->filesPath.$this->file['file'] ) ) {

					// Update clicks
					$values = array( 'clicks' => intval( $this->file['clicks'] ) + 1 );
					$GLOBALS['TYPO3_DB']->exec_UPDATEquery( 'tx_rtgfiles_files', 'uid = '.$uid, $values );

					// Redirect to external URL
					if( $this->file['url'] != '' ) {
						header( 'Location: '.$this->file['url'] );
						exit;
					}

					// Set MIME type by file extension
					$ext = strtolower( substr( ( $t = strrchr( $data['file'], '.' ) ) !== false ? $t : '' ,1 ) );
					switch( $ext ) {
						case 'pdf': $ctype = 'application/pdf'; break;
						case 'exe': $ctype = 'application/octet-stream'; break;
						case 'zip': $ctype = 'application/zip'; break;
						case 'doc': $ctype = 'application/msword'; break;
						case 'xls': $ctype = 'application/vnd.ms-excel'; break;
						case 'ppt': $ctype = 'application/vnd.ms-powerpoint'; break;
						case 'gif': $ctype = 'image/gif'; break;
						case 'png': $ctype = 'image/png'; break;
						case 'jpeg':
						case 'jpe':
						case 'jpg': $ctype = 'image/jpg'; break;
						case 'svg': $ctype = 'image/svg+xml'; break;
						case 'flv': $ctype = 'video/x-flv'; break;
						case 'mpg': $ctype = 'application/octet-stream'; break;
						case 'swf': $ctype = 'application/x-shockwave-flash'; break;
						default:    $ctype = 'application/force-download';
					}

					// Header data
					header( 'Pragma: public' );
					header( 'Expires: 0' );
					header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
					header( 'Cache-Control: private', false );
					header( 'Content-Type: '.$ctype );
					// header( 'Content-Disposition: inline; filename="'.basename( $this->file['file'] ).'"' );

					// File attachment
					header( "Content-Disposition: attachment; filename=".basename( $this->file['file'] ).";" );
					header( "Content-Transfer-Encoding: binary" );
					header( "Content-Length: ".filesize( $this->filesPath.$this->file['file'] ) );
					readfile( $this->filesPath.$this->file['file'] );
					exit;
				}
			}
		}
		return false;
	}

	/**
	 * List of files from DB
	 *
	 * @return	array		return: array of files records
	 */
	function getFiles() {

		if( !$this->error )
			$whereAnd .= '
				AND ( tx_rtgfiles_files.title LIKE \'%'.$this->valueWord.'%\'
					OR tx_rtgfiles_files.description LIKE \'%'.$this->valueWord.'%\'
					OR tx_rtgfiles_files.keywords LIKE \'%'.$this->valueWord.'%\' )';

		$query = '
			SELECT tx_rtgfiles_files.*,
				tx_rtgfiles_systems.title AS systemsTitle
			FROM tx_rtgfiles_files
				LEFT JOIN tx_rtgfiles_systems
					ON ( tx_rtgfiles_systems.uid = tx_rtgfiles_files.system
						AND tx_rtgfiles_systems.hidden = 0
						AND tx_rtgfiles_systems.deleted = 0 )
 			WHERE tx_rtgfiles_files.pid IN ( '.$this->pid.' )
				AND tx_rtgfiles_files.hidden = 0
				AND tx_rtgfiles_files.deleted = 0
				'.$whereAnd.'
			ORDER BY tx_rtgfiles_files.sorting
		';

		$res = $GLOBALS['TYPO3_DB']->sql( TYPO3_db, $query );
		if( $res ) {
			$this->num_rows = intval( $GLOBALS['TYPO3_DB']->sql_num_rows( $res ) );
			if( $this->num_rows > 0 )
				return $res;
		}
		else
			return false;
	}

	/**
	 * Get operating systems from DB
	 *
	 * @return	array		return: array of systems
	 */
	function getSystems() {

		$query = '
			SELECT uid, title
			FROM tx_rtgfiles_systems
 			WHERE hidden = 0 AND deleted = 0
			ORDER BY sorting
		';

		$res = $GLOBALS['TYPO3_DB']->sql( TYPO3_db, $query );
		$rows = array();
		if( $res )
			while( $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) )
				$rows[] = $row;
		else
			return false;

		return $rows;
	}


	/**
	 * Return human readable sizes
	 *
	 * @param	int		$size        Size
	 * @param	int		$unit        The maximum unit
	 * @param	int		$retstring   The return string format
	 * @param	int		$si          Whether to use SI prefixes
	 * @return	float		return       size of file
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.1.0
	 * @link        http://aidanlister.com/repos/v/function.size_readable.php
	 */
	function sizeReadable( $size, $unit = null, $retstring = null, $si = true ) {

	    // Units
	    if ($si === true) {
	        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB');
	        $mod   = 1000;
	    } else {
	        $sizes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
	        $mod   = 1024;
	    }
	    $ii = count($sizes) - 1;

	    // Max unit
	    $unit = array_search((string) $unit, $sizes);
	    if ($unit === null || $unit === false)
	        $unit = $ii;

	    // Return string
	    if ($retstring === null)
	        $retstring = '%01.2f %s';

	    // Loop
	    $i = 0;
	    while( $unit != $i && $size >= 1024 && $i < $ii ) {
	        $size /= $mod;
	        $i++;
	    }

	    return sprintf( $retstring, $size, $sizes[$i] );
	}


	/**
	 * Parse the subpart of the template into content with the various variables
	 *
	 * @param	string		$subpart: Needed subpart of the template
	 * @return	string		return: output template html
	 */
	function parseTemplate( $subpart ) {

		$template = array();
		$marks = array();
		$submarks = array();
		$wrappedSubpart = array();

		// Formular
		if( $subpart == 'FILES_LIST' ) {

			$template['total'] = $this->local_cObj->getSubpart( $this->totalTemplate, '###'.$subpart.'###' );
			$template['files'] = $this->local_cObj->getSubpart( $template['total'], '###FILES_LIST_ITEM###' );

			// Form attributes, titles...
			$marks['###URL###'] = 'index.php?id='.$this->id;
			$marks['###HIDDEN###'] = '<input type="hidden" class="hidden" name="no_cache" value="1" />';
			$marks['###PAGETREE###'] = $this->local_cObj->cObjGetSingle( $this->conf['pagetree'], $this->conf['pagetree.'] );

			// Any errors?
			$marks['###SUBMIT_TITLE###'] = $this->pi_getLL( 'submit_title' );
			$marks['###SUBMIT_BUTTON_LABEL###'] = $this->pi_getLL( 'submit_button_label' );
			$marks['###WORD_ERROR###'] = $this->errorWord != '' ? str_replace( "|", $this->errorWord, $this->wrapErrors ): '';
			$marks['###SYSTEMS_ERROR###'] = $this->errorSystem != '' ? str_replace( "|", $this->errorSystem, $this->wrapErrors ): '';

			// Form values form post
			$marks['###WORD_VALUE###'] = $this->valueWord ? $this->valueWord: '';

			// Systems selects
			$listSystems = $this->getSystems();
			if( count( $listSystems ) > 0 )
				foreach( $listSystems as $itemS )
					$marks['###SYSTEMS_SELECT###'] .= '<option value="'.$itemS['uid'].'" '.( $this->valueSystem == $itemS['uid'] ? 'selected="selected"': '' ).'>'.stripslashes( $itemS['title'] ).'</option>';
			else
				$marks['###SYSTEMS_SELECT###'] = '';

			// Files list
			$res = $this->getFiles();
			if( $this->num_rows ) {

				// Fields titles
				$submarks['###TSTAMP_TITLE###'] = $this->pi_getLL( 'tx_rtgfiles_files.date' );
				$submarks['###CLICKS_TITLE###'] = $this->pi_getLL( 'tx_rtgfiles_files.clicks' );
				$submarks['###SIZE_TITLE###'] = $this->pi_getLL( 'tx_rtgfiles_files.size' );

				while( $itemF = $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res ) ) {

					$submarks['###TITLE###'] = stripslashes( $itemF['title'] );
					$submarks['###DESCRIPTION###'] = $this->local_cObj->stdWrap( $itemF['description'], $this->conf['description_stdWrap.'] );
					$submarks['###TSTAMP###'] = $this->local_cObj->stdWrap( $itemF['tstamp'], $this->conf['tstamp_stdWrap.'] );
					$submarks['###CLICKS###'] = $itemF['clicks'];
					$subsubparts = array();
					// File information
					$file = explode( '.', $itemF['file'] );
					$extPos = count( $file ) - 1;

					// External URL or document file?
					if( $itemF['url'] != '' ) {
						$submarks['###FILE_NAME_TITLE###'] = $this->pi_getLL( 'tx_rtgfiles_files.url' );
						$submarks['###FILE_NAME###'] = $itemF['url'];
						$submarks['###TYPE_IMAGE###'] = $this->conf['types.']['html.']['image'] != '' ? '<img src="'.$this->conf['iconsUploadPath'].$this->conf['types.']['html.']['image'].'" alt="'.$this->conf['types.']['html.']['title'].'" />': '';
						// Unknown file size
						$submarks['###SIZE###'] = $this->pi_getLL( 'tx_rtgfiles_files.size_unknown' );
						$subsubparts['###SIZE_BOX###'] = '';
						$wrappedSubpart['###FILE_LINK###'] = array( '<a href="'.$itemF['url'].'" target="_blank">', '</a>' );
						// Link to download script
						$params = array(
							$this->prefixId.'[cmd]' => 'DOWNLOAD',
							$this->prefixId.'[uid]' => intval( $itemF['uid'] ),
						);
						$link = '<a href="'.str_replace( '&', '&amp;', $this->pi_getPageLink( $this->id, '_self', $params ) ).'" target="_self">';
						$wrappedSubpart['###FILE_DOWNLOAD###'] = array( $link, '</a>' );
					}
					else { // File
						$submarks['###FILE_NAME_TITLE###'] = $this->pi_getLL( 'tx_rtgfiles_files.file' );
						$submarks['###FILE_NAME###'] = $itemF['file'];
						$submarks['###TYPE_IMAGE###'] = $this->conf['types.'][$file[$extPos].'.']['image'] != '' ? '<img src="'.$this->conf['iconsUploadPath'].$this->conf['types.'][$file[$extPos].'.']['image'].'" alt="'.$this->conf['types.'][$file[$extPos].'.']['title'].'" />': '';
						$submarks['###SIZE###'] = $this->sizeReadable( filesize( $this->filesPath.$itemF['file'] ), 'kB', $this->conf['size_stdWrap'] );
						$wrappedSubpart['###FILE_LINK###'] = array( '<a href="'.$this->filesPath.$itemF['file'].'" target="_blank">', '</a>' );
						// Link to download script
						$params = array(
							$this->prefixId.'[cmd]' => 'DOWNLOAD',
							$this->prefixId.'[uid]' => intval( $itemF['uid'] ),
						);
						$link = '<a href="'.str_replace( '&', '&amp;', $this->pi_getPageLink( $this->id, '_self', $params ) ).'" target="_self">';
						$wrappedSubpart['###FILE_DOWNLOAD###'] = array( $link, '</a>' );
					}
					$content_row .= $this->cObj->substituteMarkerArrayCached( $template['files'], $submarks, $subsubparts, $wrappedSubpart );
				}
			}
			else
				$content_row = '<tr><td colspan="3">'.$this->pi_getLL( 'empty_list' ).'</td></tr>';

			// Final content template
			$subparts['###FILES_LIST_ITEM###'] = $content_row;
			$template = $this->cObj->substituteMarkerArrayCached( $template['total'], $marks, $subparts, $subparts );
			return $template;
		}

		return ''; // Empty output, no subpart
	}


} // end class tx_rtgfiles_pi1



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rtg_files/pi1/class.tx_rtgfiles_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/rtg_files/pi1/class.tx_rtgfiles_pi1.php']);
}

?>