<?php

namespace Skapitza\GoogleCharts\Hooks;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DataHandler {

	/**
	 *
	 * @param string $status status
	 * @param string $table table name
	 * @param int $recordUid id of the record
	 * @param array $fields fieldArray
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject parent Object
	 * @return void
	 */
	public function processDatamap_afterAllOperations(
	\TYPO3\CMS\Core\DataHandling\DataHandler $pObj
	) {
		foreach ($pObj->datamap as $table => $data) {
			foreach ($data as $id => $fields) {
				/** @var $extbaseObjectManager \TYPO3\CMS\Extbase\Object\ObjectManager */
				$extbaseObjectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
				/** @var $persistenceManager \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager */
				$persistenceManager = $extbaseObjectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
				/** @var $chartRepository \Tx_GoogleCharts_Domain_Repository_ChartRepository */
				$chartRepository = $extbaseObjectManager->get('Skapitza\GoogleCharts\Domain\Repository\ChartRepository');
				/** @var $chartRecord \Skapitza\GoogleCharts\Domain\Model\Chart */
				$chartRecord = $chartRepository->findByUidAndLang($id, intval($fields['sys_language_uid']));
				//$chartRecord = $chartRepository->findByUid($id);
				//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($chartRecord[0]);
				$chartRecord = $this->setSheetToJson($chartRecord[0]);
				$chartRepository->update($chartRecord);
				$persistenceManager->persistAll();
			}
		}
	}

	/**
	 * 
	 * @param Chart $chart
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function setSheetToJson($chart) {
		$json = NULL;

		$chartid = $chart->getSheet();
		$json = file_get_contents('https://docs.google.com/spreadsheets/d/' . $chartid . '/gviz/tq?headers=1');
		$jsonstr = str_replace('/*O_o*/', '', $json);
		$jsonstr = str_replace('google.visualization.Query.setResponse(', '', $jsonstr);
		$jsonstr = str_replace(');', '', $jsonstr);
		$chart->setJson($jsonstr);
		return $chart;
	}

}
