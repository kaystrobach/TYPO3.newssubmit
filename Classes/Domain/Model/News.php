<?php
namespace T3ext\Newssubmit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Kay Strobach <typo3@kay-strobach.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use GeorgRinger\News\Domain\Model\FileReference;

/**
 * @package newssubmit
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class News extends \GeorgRinger\News\Domain\Model\News {

	/**
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * @var string
	 * @validate NotEmpty
	 */
	protected $bodytext;

	/**
	 * @var string
	 * @validate NotEmpty
	 */
	protected $author;

	/**
	 * @var string
	 * @validate NotEmpty
	 * @validate EmailAddress
	 */
	protected $authorEmail;

	/**
	 * Add a Fal media file reference
	 *
	 * @param FileReference $falMedia
	 */
	public function addFalMedia(FileReference $falMedia)
	{
		if (!$falMedia) {
			return;
		}
		if ($this->getFalMedia() === null) {
			$this->falMedia = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->falMedia->attach($falMedia);
	}
}
