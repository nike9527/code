<?php
abstract class IAbstract
{
	protected $valueNow;
	abstract protected function giveCost();
	abstract protected function giveCity();
	public function displayShow()
	{
		$stringCost = $this->giveCost();
		$stringCost = (string)$stringCost;
		$allToGether = "const: $".$stringCost."for".$this->giveCity();
	}
}

class NorthRegion extends IAbstract{
	protected function giveCost{
		return 20.54;
	}
	protected function giveCost{
		return "Moose Breath";
	}
}


class Westected extends IAbstract
{
	
	protected function giveCost{
		$solarSaving = 2;
		$this->valueNow = 20.54/$solarSaving;
		return $this->valueNow;
	}
	protected function giveCost{
		return "Rattlesmake Gulch";
	}
	
}

class client
{
	public function __construct()
	{
		$north = new NorthRegion();
		$west = new Westected();
		$this->showInterface($north);
		$this->showInterface($north);
	}
	private function showInterface(IAbstract $region)
	{
			echo $region->displayShow().PHP_EOL;
	}
}

$work = new client();