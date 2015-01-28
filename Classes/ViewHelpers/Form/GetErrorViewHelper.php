<?php
namespace MiniFranske\Newssubmit\ViewHelpers\Form;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Frans Saris <franssaris@gmail.com>
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

use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Error;

/**
 * Get Error for given property
 */
class GetErrorViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper {

	/**
	 * @param string $wrap
	 * @return string
	 */
	public function render($wrap = '<span class="help-block">|</span>') {

		/** @var Result $result */
		$result = $this->getMappingResultsForProperty();
		if ($result->hasErrors()) {
			$wrap = explode('|', $wrap);
			return $wrap[0] . implode(', ', $this->getErrorMessages($result)) . (isset($wrap[1]) ? $wrap[1] : '');
		} else {
			return '';
		}
	}

	/**
	 * @param Result $result
	 * @return array
	 */
	protected function getErrorMessages(Result $result) {
		$messages = array();
		/** @var Error $error */
		foreach ($result->getErrors() as $error) {
			$messages[] = htmlspecialchars(
				LocalizationUtility::translate('formError.' . $error->getCode(), 'BiMarketplace') ?: $error->getMessage()
			);
		}
		return $messages;
	}
}