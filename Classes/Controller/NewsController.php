<?php

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

/**
 *
 *
 * @package newssubmit
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Newssubmit_Controller_NewsController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * newsRepository
	 *
	 * @var Tx_Newssubmit_Domain_Repository_NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var Tx_News_Domain_Repository_CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var Tx_Extbase_Domain_Repository_FrontendUserRepository
	 */
	protected $frontendUserRepository = NULL;

	/**
	 * Disable default Error FlashMessage
	 *
	 * @return string|boolean The flash message or FALSE if no flash message should be set
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}

	/**
	 * action new
	 *
	 * @param Tx_Newssubmit_Domain_Model_News $newNews
	 * @dontvalidate $newNews
	 * @return void
	 */
	public function newAction(Tx_Newssubmit_Domain_Model_News $newNews = NULL) {

		if ($newNews === NULL && $this->getFeUser()) {
			$feUser = $this->getFeUser();
			$newNews = new Tx_Newssubmit_Domain_Model_News();
			$newNews->setAuthor($feUser->getName());
			$newNews->setAuthorEmail($feUser->getEmail());
		}

		$this->view->assign('newNews', $newNews);
		if (!empty($this->settings['categories']['enabled'])) {
			$this->view->assign('categories', $this->categoryRepository->findAll());
		}
		$this->view->assign('feUser', $this->getFeUser());
	}

	/**
	 * action create
	 *
	 * @param Tx_Newssubmit_Domain_Model_News $newNews
	 * @param string $link
	 * @return void
	 */
	public function createAction(Tx_Newssubmit_Domain_Model_News $newNews, $link = '') {
		$newNews->setDatetime(new DateTime());

		if (empty($this->settings['enableNewItemsByDefault'])) {
			$newNews->setHidden(1);
		}

		// cleanup empty category
		// todo: find cleaner way for this
		foreach ($newNews->getCategories() as $category) {
			if ($category === NULL) {
				$newNews->getCategories()->detach($category);
			}
		}

		if($link !== '') {
			$linkObject = new Tx_News_Domain_Model_Link();
			$linkObject->setUri($link);
			$linkObject->setTitle($link);
			$newNews->addRelatedLink($linkObject);
		}

		// todo: add error handling
		if(!empty($_FILES['tx_newssubmit_newssubmit']['name']['image'])) {
			$basicFileFunctions = t3lib_div::makeInstance('t3lib_basicFileFunctions');
			$fileName = $basicFileFunctions->getUniqueName(
				$_FILES['tx_newssubmit_newssubmit']['name']['image'],
				t3lib_div::getFileAbsFileName('uploads/tx_news/')
			);
			t3lib_div::upload_copy_move(
				$_FILES['tx_newssubmit_newssubmit']['tmp_name']['image'],
				$fileName
			);
			$imageObject = new Tx_News_Domain_Model_Media();
			$imageObject->setTitle($newNews->getTitle());
			$imageObject->setImage(basename($fileName));
			$imageObject->setShowinpreview(1);
			$newNews->addMedia($imageObject);
		}

		// todo: add error handling
		if(!empty($_FILES['tx_newssubmit_newssubmit']['name']['attachment'])) {
			$basicFileFunctions = t3lib_div::makeInstance('t3lib_basicFileFunctions');
			$fileName = $basicFileFunctions->getUniqueName(
				$_FILES['tx_newssubmit_newssubmit']['name']['attachment'],
				t3lib_div::getFileAbsFileName('uploads/tx_news/')
			);
			t3lib_div::upload_copy_move(
				$_FILES['tx_newssubmit_newssubmit']['tmp_name']['attachment'],
				$fileName
			);
			$attachmentObject = new Tx_News_Domain_Model_File();
			$attachmentObject->setTitle(basename($fileName));
			$attachmentObject->setFile(basename($fileName));
			$newNews->addRelatedFile($attachmentObject);
		}

		// save news
		$this->newsRepository->add($newNews);
		$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('news.created', $this->controllerContext->getRequest()->getControllerExtensionName()));

		// send mail
		if($this->settings['recipientMail']) {
			/** @var $message t3lib_mail_Message */
			$message = t3lib_div::makeInstance('t3lib_mail_Message');
			$from    = t3lib_utility_Mail::getSystemFrom();
			$message->setFrom($from)
				->setTo(array($this->settings['recipientMail'] => 'News Manager'))
				->setSubject('[New News] ' . $newNews->getTitle())
				->setBody('<h1>New News</h1>' . $newNews->getBodytext(), 'text/html')
				->send();
		}

		// go to thank you action
		$this->redirect('thankyou');
	}

	/**
	 * injectNewsRepository
	 *
	 * @param Tx_Newssubmit_Domain_Repository_NewsRepository $newsRepository
	 * @return void
	 */
	public function injectNewsRepository(Tx_Newssubmit_Domain_Repository_NewsRepository $newsRepository) {
		$this->newsRepository = $newsRepository;
	}

	/**
	 * injectCategoriesRepository
	 *
	 * @param Tx_News_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 *
	 * @param Tx_Extbase_Domain_Repository_FrontendUserRepository $frontendUserRepository
	 */
	public function injectFrontendUserRepository(Tx_Extbase_Domain_Repository_FrontendUserRepository $frontendUserRepository) {
		$this->frontendUserRepository = $frontendUserRepository;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function thankyouAction() {

	}

	/**
	 * Get current logged-in user
	 *
	 * @return null|Tx_Extbase_Domain_Model_FrontendUser
	 */
	public function getFeUser() {
		static $frontEndUser;

		if ($frontEndUser === NULL && isset($GLOBALS['TSFE']) && $GLOBALS['TSFE']->loginUser && $GLOBALS['TSFE']->fe_user->user['uid']) {
			$frontEndUser = $this->frontendUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
		}

		return $frontEndUser;
	}

}
