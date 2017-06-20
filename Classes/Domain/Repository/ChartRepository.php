<?php
namespace Skapitza\GoogleCharts\Domain\Repository;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use TYPO3\CMS\Extbase\Persistence\Repository;

class ChartRepository extends Repository {
	
	public function initializeObject() {
		/** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
		$querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		
		$querySettings->setRespectStoragePage(FALSE);
		
		$this->setDefaultQuerySettings($querySettings);
	}
	
	public function findByUidAndLang($uid, $lang) {
		$query = $this ->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setLanguageUid($lang);
        $query->matching(
            $query->logicalOR(
                $query->equals('uid', $uid),
                $query->equals('sys_language_uid', $lang)
            )
        );
        return $query->execute();
	}
	
}