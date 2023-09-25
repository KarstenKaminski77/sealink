<?php
/*
	Copyright (c) InterAKT Online 2000-2005
*/
/**
 * This class delete a record from the given table, field;
 * @access public
 */
	class tNG_DeleteDetailRec {
		/**
	 	* The reference to transaction object
	 	* @var object tNG transaction object
	 	* @access public
	 	*/
		var $tNG;
		/**
	 	* name of the table
	 	* @var string
	 	* @access public
	 	*/
		var $table;
		/**
	 	* Name of the field
	 	* @var string
	 	* @access public
	 	*/
		var $field;
		
		/**
		 * Constructor. Sets the transaction and some defaults values for table/field.
		 * @param object tNG  The reference to transaction object
		 * @access public
		 */
		function tNG_DeleteDetailRec(&$tNG) {
			$this->tNG = &$tNG;
			$this->table = 'mytable';
			$this->field = 'myfield';
		}
		/**
		 * setter. sets the name of the table
		 * @param string 
		 * @access public
		 */
		function setTable($table) {
			$this->table = $table;
		}
		/**
		 * setter. sets the name of the field
		 * @param string
		 * @access public
		 */
		function setFieldName($field) {
			$this->field = $field;
		}
		/**
		 * contruct the SQL and execute it. it is using as value for the field the primarey key value from the transaction;
		 * return mix null or error object;
		 * @access public
		 */
		function Execute() {
			$pk_value = $this->tNG->getPrimaryKeyValue();
			$pk_type = $this->tNG->getColumnType($this->tNG->getPrimaryKey());
			$pk_value = KT_escapeForSql($pk_value, $pk_type);
			$sql = "DELETE FROM " . $this->table . " WHERE " . KT_escapeFieldName($this->field) . " = " . $pk_value;
			$ret = $this->tNG->connection->Execute($sql);
			if ($ret === false) {
				return new tNG_error('DEL_DR_SQL_ERROR', array(), array($this->tNG->connection->ErrorMsg(), $sql));
			}
			return null;
		}
	}
?>