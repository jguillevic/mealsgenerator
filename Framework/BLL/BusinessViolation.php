<?php

namespace Framework\BLL;

use \Framework\BLL\ViolationKind;

class BusinessViolation
{
	private $violationKind;
	private $message;

	public function __construct($violationKind, $message)
	{
		$this->SetViolationKind($violationKind);
		$this->SetMessage($message);
	}

	public function GetViolationKind()
	{
		return $this->violationKind;
	}

	public function SetViolationKind($value)
	{
		$this->violationKind = $value;

		return $this;
	}

	public function GetMessage()
	{
		return $this->message;
	}

	public function SetMessage($value)
	{
		$this->message = $value;

		return $this;
	}

	public static function CreateBusinessInfo($message)
	{
		$bv = new BusinessViolation(violationKind::Info, $message);

		return $bv;
	}

	public static function CreateBusinessWarning($message)
	{
		$bv = new BusinessViolation(violationKind::Warning, $message);

		return $bv;
	}

	public static function CreateBusinessError($message)
	{
		$bv = new BusinessViolation(violationKind::Error, $message);

		return $bv;
	}
}