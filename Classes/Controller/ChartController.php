<?php

namespace Skapitza\GoogleCharts\Controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Skapitza\GoogleCharts\Domain\Model\Chart;
use Skapitza\GoogleCharts\Domain\Repository\ChartRepository;

class ChartController extends ActionController {

	/**
	 *
	 * @var \Skapitza\GoogleCharts\Domain\Repository\ChartRepository
	 * 
	 * @inject
	 */
	protected $chartRepository;

	/**
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	public function showAction() {
		$data = $this->settings['data'];
		if ($data != '') {
			$chart = $this->chartRepository->findByUid($data);
			$chartArr = $chart;
			if ($chart->getJson() == '' || $chart->getJson() == NULL)
				$chartArr = $this->setSheetToJson($chart);
			//$chartArr['json'] = unserialize($chartArr['json']);
			$this->view->assign('chart', $chartArr);
		}
		//$this->view->assign('json', json_encode(unserialize($chartArr['json'])));

		$currentContentObject = $this->configurationManager->getContentObject()->data;
		$this->view->assign('data', $currentContentObject);
		$GLOBALS['TSFE']->additionalHeaderData['tx_google_charts'] = '<script type="text/javascript" src="typo3conf/ext/google_charts/Resources/Public/Js/loader.js"></script>';
		$GLOBALS['TSFE']->additionalFooterData['tx_google_charts'] = '<script type="text/javascript" src="typo3conf/ext/google_charts/Resources/Public/Js/script.js"></script>';
	}

	/**
	 * 
	 * @param Chart $chart
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function setSheetToJson(Chart $chart) {
		$json = NULL;

		$chartid = $chart->getSheet();
		$json = file_get_contents('https://docs.google.com/spreadsheets/d/' . $chartid . '/gviz/tq?headers=1');
		$jsonstr = str_replace('/*O_o*/', '', $json);
		$jsonstr = str_replace('google.visualization.Query.setResponse(', '', $jsonstr);
		$jsonstr = str_replace(');', '', $jsonstr);
		$chart->setJson($jsonstr);
		$this->chartRepository->update($chart);
		return $chart;
	}

}
