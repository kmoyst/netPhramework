<?php

namespace netPhramework\db\mysql;

use netPhramework\common\DateValidator;
use netPhramework\common\NotNullValidator;
use netPhramework\db\exceptions\MysqlException;
use netPhramework\db\mapping\Field;
use netPhramework\db\mapping\FieldSet;
use netPhramework\db\mapping\FieldType;
use netPhramework\db\mysql\queries\ShowColumns;

class FieldMapper
{
	private FieldSet $fieldSet;
	private Field $primary;

	/**
	 * @param ShowColumns $query
	 * @return void
	 * @throws MysqlException
	 */
	public function map(ShowColumns $query):void
	{
		$fieldSet = new FieldSet();
		foreach($query->provideSqlColumns() as $sqlColumn)
		{
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
			if($sqlColumn['Key'] === 'PRI')
				$this->primary = $field;
			else
				$fieldSet->add($field);
		}
		$this->fieldSet = $fieldSet;
	}

	private function mapType($sqlType, Field $column):void
	{
		if(preg_match("|^varchar\(([0-9]+)\)$|",$sqlType,$m)) {
			$column->setType(FieldType::STRING);
			$column->setMaxLength($m[1]);
		} elseif(preg_match('|text$|',$sqlType)) {
			$column->setType(FieldType::PARAGRAPH);
		} elseif (preg_match('/^(boolean)|(tinyint\(1\))/',$sqlType)) {
			$column->setType(FieldType::BOOLEAN);
		} elseif(preg_match('|(int)|',$sqlType)) {
			$column->setType(FieldType::INTEGER);
		} elseif(preg_match('/^(float)|(decimal)/',$sqlType)) {
			$column->setType(FieldType::FLOAT);
		} elseif(preg_match('|^date|',$sqlType)) {
			$column->setType(FieldType::DATE);
			$column->addValidator(new DateValidator());
		} elseif(preg_match('|^time|',$sqlType)) {
			$column->setType(FieldType::TIME);
		} else {
			$column->setType(FieldType::STRING);
		}
	}

	public function getFieldSet():FieldSet
	{
		return $this->fieldSet;
	}

	public function getPrimary():Field
	{
		return $this->primary;
	}
}