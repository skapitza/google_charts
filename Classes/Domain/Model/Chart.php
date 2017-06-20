<?php

namespace Skapitza\GoogleCharts\Domain\Model;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Chart extends AbstractEntity {
	/**
	 *
	 * @var string 
	 */
	protected $title = '';
	
	/**
	 *
	 * @var string
	 */
	protected $sheet = '';
	
	/**
	 *
	 * @var string 
	 */
	protected $json;
	
	/**
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * 
	 * @param string $title
	 * 
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getSheet() {
		return $this->sheet;
	}
	
	/**
	 * 
	 * @param string $sheet
	 * 
	 * @return void
	 */
	public function setSheet($sheet) {
		$this->sheet = $sheet;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getJson() {
		return $this->json;
	}
	
	/**
	 * 
	 * @param string $json
	 * 
	 * @return void
	 */
	public function setJson($json) {
		$this->json = $json;
	}
}
