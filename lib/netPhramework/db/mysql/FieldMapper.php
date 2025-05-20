<?php

namespace netPhramework\db\mysql;

use netPhramework\db\core\FieldSet;
use netPhramework\db\core\Field;
use netPhramework\db\core\FieldType;
use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\validators\DateValidator;
use netPhramework\db\validators\NotNullValidator;

class FieldMapper
{
	private FieldSet $fieldSet;
	private string $primaryKey;

	/**
	 * @param FieldQuery $query
	 * @return void
	 * @throws MysqlException
	 */
	public function map(FieldQuery $query):void
	{
		$fieldSet = new FieldSet();
		foreach($query->provideSqlColumns() as $sqlColumn)
		{
			if($sqlColumn['Key'] === 'PRI') {
				$this->primaryKey = $sqlColumn['Field'];
				continue;
			}
			$field = new Field();
			if($sqlColumn['Key'] === 'UNI') {
				$field->setMustBeUnique(true);
			} else {
				$field->setMustBeUnique(false);
			}
			$field->setName($sqlColumn['Field']);
			if($sqlColumn['Null'] === 'YES')
				$field->setAllowsNull(true);
			else {
				$field->setAllowsNull(false);
				$field->addValidator(new NotNullValidator());
			}
			$this->mapType($sqlColumn['Type'], $field);
			$fieldSet->add($field);
		}
		$this->fieldSet = $fieldSet;
	}

	private function mapType($sqlType, Field $column):void
	{
		if(preg_match("|^varchar\(([0-9]+)\)$|",$sqlType,$m)) {
			$column->setType(FieldType::STRING);
			$column->setMaxLength($m[1]);
		} elseif(preg_match('|^text$|',$sqlType)) {
			$column->setType(FieldType::PARAGRAPH);
		} elseif(preg_match('|(int)|',$sqlType)) {
			$column->setType(FieldType::INTEGER);
		} elseif(preg_match('/^(float)|(decimal)/',$sqlType)) {
			$column->setType(FieldType::FLOAT);
		} elseif(preg_match('|^date|',$sqlType)) {
			$column->setType(FieldType::DATE);
			$column->addValidator(new DateValidator());
		} elseif(preg_match('|^time|',$sqlType)) {
			$column->setType(FieldType::TIME);
		} elseif (preg_match('|^boolean|',$sqlType)) {
			$column->setType(FieldType::BOOLEAN);
		} else {
			$column->setType(FieldType::STRING);
		}
	}

	public function getFieldSet():FieldSet
	{
		return $this->fieldSet;
	}

	public function getPrimaryKey():string
	{
		return $this->primaryKey;
	}
}