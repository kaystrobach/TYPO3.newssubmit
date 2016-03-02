<?php
namespace T3ext\Newssubmit\Controller;

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

use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use T3ext\Newssubmit\Domain\Model\News;
use T3ext\Newssubmit\Domain\Repository\NewsRepository;
use T3ext\Newssubmit\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 *
 *
 * @package newssubmit
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class NewsController extends ActionController {

	/**
	 * newsRepository
	 *
	 * @var \T3ext\Newssubmit\Domain\Repository\NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
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
	 * Set TypeConverter option for file upload
	 */
	public function initializeCreateAction() {
		$this->setTypeConverterConfigurationForFileUpload('newNews');
	}

	/**
	 * Set TypeConverter option for file upload
	 */
	public function initializeUpdateAction() {
		$this->setTypeConverterConfigurationForFileUpload('news');
	}

	/**
	 * Set file upload type converters
	 *
	 * @param $argumentName
	 */
	protected function setTypeConverterConfigurationForFileUpload($argumentName) {
		$mediaUploadConfiguration = array(
			UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
			UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => $this->settings['imagesFolder'],
		);
		$relatedFileUploadConfiguration = array(
			UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => NULL,
			UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => $this->settings['attachmentsFolder'],
		);
		/** @var PropertyMappingConfiguration $newsConfiguration */
		$newsConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
		for ($i = 0; $i < (int)$this->settings['image']['count']; $i++) {
			$newsConfiguration->forProperty('falMedia.' . $i)
				->setTypeConverterOptions(
					'T3ext\\Newssubmit\\Property\\TypeConverter\\UploadedFileReferenceConverter',
					$mediaUploadConfiguration
				);
		}
		for ($i = 0; $i < (int)$this->settings['attachment']['count']; $i++) {
			$newsConfiguration->forProperty('falRelatedFiles.' . $i)
				->setTypeConverterOptions(
					'T3ext\\Newssubmit\\Property\\TypeConverter\\UploadedFileReferenceConverter',
					$relatedFileUploadConfiguration
				);
		}
	}

	/**
	 * action new
	 *
	 * @param News $newNews
	 * @dontvalidate $newNews
	 * @return void
	 */
	public function newAction(News $newNews = NULL) {

		if ($newNews === NULL && $this->getFeUser()) {
			$feUser = $this->getFeUser();
			$newNews = new News();
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
	 * @param News $newNews
	 * @param string $link
	 * @return void
	 */
	public function createAction(News $newNews, $link = '') {
		$newNews->setDatetime(new \DateTime());

		if (empty($this->settings['enableNewItemsByDefault'])) {
			$newNews->setHidden(1);
		}

		if($link !== '') {
			$linkObject = new Link();
			$linkObject->setUri($link);
			$linkObject->setTitle($link);
			$newNews->addRelatedLink($linkObject);
		}

		// save news
		$this->newsRepository->add($newNews);
		$this->addFlashMessage(LocalizationUtility::translate('news.created', $this->extensionName));

		// send mail
		if($this->settings['recipientMail']) {
			/** @var $message \TYPO3\CMS\Core\Mail\MailMessage */
			$message = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
			$from = MailUtility::getSystemFrom();
			$message->setFrom($from)
				->setTo(array($this->settings['recipientMail'] => 'News Manager'))
				->setSubject('[New News] ' . $newNews->getTitle())
				->setBody('<h1>New News</h1>' . $newNews->getBodytext(), 'text/html')
				->send();
		}

		// clear page cache after save
		if (!$newNews->getHidden()) {
			$this->flushNewsCache($newNews);
		}

		// go to thank you action
		$this->redirect('thankyou');
	}

	/**
	 * Flush cache (like in GeorgRinger\News\Hooks\DataHandler::clearCachePostProc())
	 *
	 * @param News $news
	 */
	protected function flushNewsCache(News $news) {
		$cacheTagsToFlush = array();
		if ($news->getUid()) {
			$cacheTagsToFlush[] = 'tx_news_uid_' . $news->getUid();
		}
		if ($news->getPid()) {
			$cacheTagsToFlush[] = 'tx_news_pid_' . $news->getPid();
		}

		/** @var $cacheManager \TYPO3\CMS\Core\Cache\CacheManager */
		$cacheManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
		foreach ($cacheTagsToFlush as $cacheTag) {
			$cacheManager->getCache('cache_pages')->flushByTag($cacheTag);
			$cacheManager->getCache('cache_pagesection')->flushByTag($cacheTag);
		}
	}

	/**
	 * injectNewsRepository
	 *
	 * @param NewsRepository $newsRepository
	 * @return void
	 */
	public function injectNewsRepository(NewsRepository $newsRepository) {
		$this->newsRepository = $newsRepository;
	}

	/**
	 * injectCategoriesRepository
	 *
	 * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * Inject Frontend User Repository
	 *
	 * @param FrontendUserRepository $frontendUserRepository
	 */
	public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository) {
		$this->frontendUserRepository = $frontendUserRepository;
	}

	/**
	 * action list
	 *
	 * @param News $news
	 * @return void
	 */
	public function thankyouAction(News $news = NULL) {

	}

	/**
	 * Get current logged-in user
	 *
	 * @return null|FrontendUser
	 */
	public function getFeUser() {
		static $frontEndUser;

		if ($frontEndUser === NULL && isset($GLOBALS['TSFE']) && $GLOBALS['TSFE']->loginUser && $GLOBALS['TSFE']->fe_user->user['uid']) {
			$frontEndUser = $this->frontendUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
		}

		return $frontEndUser;
	}

	/**
	 * Edit news item
	 *
	 * @param News $news
	 */
	public function editAction(News $news) {
		$this->view->assign('news', $news);
		if (!empty($this->settings['categories']['enabled'])) {
			$this->view->assign('categories', $this->categoryRepository->findAll());
		}
	}

	/**
	 * Update news item
	 *
	 * @param News $news
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function updateAction(News $news) {
		$this->newsRepository->update($news);

		// clear page cache after save
		if (!$news->getHidden()) {
			$this->flushNewsCache($news);
		}

		$this->redirect('list');
	}

	/**
	 * list available news
	 */
	public function listAction() {
		$this->view->assign('news', $this->newsRepository->findAll());
	}
}
