<?php

namespace Framework\DAL;

class Database
{
	private $connect;
	private $transactionCount = 0;

	public function __construct()
	{
		$this->connect = self::GetPDO();

		self::SetCharacterSetUTF8($this->connect);
		self::SetErrorMode($this->connect);
		self::SetAutoCommit($this->connect);
	}

	public function __destruct()
	{
		if ($this->transactionCount > 0)
			self::Rollback();
	}

	private static function GetConfig()
	{
		$configPath = join(DIRECTORY_SEPARATOR, array(__DIR__, '..' , '..', 'Config', 'Database.json'));

		$json = file_get_contents($configPath);

		$config = json_decode($json, true);

		return $config;
	}

	private static function GetPDO()
	{
		$config = self::GetConfig();

		return new \PDO("mysql:host=".$config["Host"]."; dbname=".$config["DbName"]."", $config["Username"], $config["Password"]);
	}

	public function GetLastInsertId()
	{
		return $this->connect->lastInsertId();
	}

	private static function SetCharacterSetUTF8($connect)
	{
		$connect->exec("SET CHARACTER SET utf8");
	}

	private static function SetErrorMode($connect)
	{
		$connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	private static function SetAutoCommit($connect)
	{
		$connect->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
	}

	public function Execute($query, $params = null)
	{
		$stmt = $this->connect->prepare($query);

		if (isset($params))
			$stmt->execute($params);
		else
			$stmt->execute();

		return $stmt;
	}

	public function Read($query, $params = null)
	{
		$stmt = $this->Execute($query, $params);

		$result = $stmt->fetchAll();

		return $result;
	}

	public function BeginTransaction()
	{
		if ($this->transactionCount == 0)
			$this->connect->beginTransaction();

		$this->transactionCount++;
	}

	public function Commit()
	{
		$this->transactionCount--;

		if ($this->transactionCount == 0)
			$this->connect->commit();
	}

	public function Rollback()
	{
		$this->connect->rollback();

		$this->transactionCount = 0;
	}
}