<?php

namespace Framework\BLL;

class ViolationKind
{
	const __default = self::Info;

	const Info = 1;
	const Warning = 2;
	const Error = 3;
}