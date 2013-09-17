<?php
/**
 * 
 * @author james.turner
 *
 */
class Blackflag_Db_Date {
	
	const PHP_DEFAULT_DATE = 'Y-m-d';
	const PHP_ORACLE_DATE = 'j-M-Y';
	const PHP_MYSQL_DATE = 'Y-m-d';
	
	/**
	 * 
	 * @var Zend_Date
	 */
	private $date;
	
	/**
	 * 
	 * @var string
	 */
	private $dbAdapter;
	
	public function __construct(array $options = array()){
		$this->setOptions($options);
	}
	
	public function setOptions(array $options){
		
		switch(true){
			case isset($options['date']):
				$this->setDate($options['date']);
			case isset($options['dbAdapter']):
				$this->setDbAdapter($options['dbAdapter']);
			default;
		}
		
	}
	
	public function setDate($date){
		
		if(!$date instanceof Zend_Date){
			$date = new Zend_Date($date);
		}
		
		$this->date = $date;
	}
	
	public function getDate(){
		if(null === $this->date){
			return new Zend_Date();
		}
		return $this->date;
	}
	
	public function setDbAdapter($dbAdapter){
		
		if($dbAdapter instanceof Zend_Db_Adapter_Abstract){
			$this->dbAdapter = get_class($dbAdapter);
		} else {
			$this->dbAdapter = $dbAdapter;
		}	
	}
	
	public function toDbExpr(){
		
		$format = self::PHP_DEFAULT_DATE;
		$expression = "'%s'";
		switch($this->dbAdapter){
			case "Zend_Db_Adapter_Pdo_Oci":
			case "Zend_Db_Adapter_Oracle":
				$format = self::PHP_ORACLE_DATE;
				break;
			case "Zend_Db_Adapter_Pdo_Mysql":
			case "Zend_Db_Adapter_Mysqli":
			default:
				$format = self::PHP_MYSQL_DATE;
				break;
		}
		
		$date = $this->getDate()->toString($format, 'php'); // Utilises the PHP format type.
		
		return new Zend_Db_Expr(sprintf($expression, $date));
		
	}
	
}