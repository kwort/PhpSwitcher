<?php

class PhpSwitch
{
	private $version;
	private $arguments;

	const XAMPP = "/opt/lampp";

	/**
	 * Determine l'etat de xampp, si il y a un processus httd en cours d'execution :
	 * alors xampp est lancé, sinon il est stopé
	 */
	private function isXamppStart()
	{
		$cmd = `netstat -tunap|grep LISTEN`;
		$isXamppStart = preg_match('/httpd/', $cmd);

		return $isXamppStart;
	}

	/**
	 * Utilisation du script xampp linké sur le lien symbolique
	 * @param String cmdArg param passé à xampp
	 */
	private function xampp($cmdArg)
	{
		$cmd = self::XAMPP."/xampp ".$cmdArg;
		$return = `$cmd`;

		echo $return;
	}

	/**
	 * Renvoi les versions de xampp installé par rapport au repertoire de xampp
	 * avec un suffixe de version
	 */
	private function getXamppVersions()
	{
		if (!file_exists(self::XAMPP)) {
			throw new Exception("Le lien symbolique de xampp n'existe pas", 1);
		}

		$path = dirname(self::XAMPP);
		$dirs = array_diff(scandir($path), array('..', '.'));
		$versions = [];

		foreach ($dirs as $file) {
			$match = preg_match('/lampp(\d\d)/', $file, $matches);
			if ($match && is_array($matches) && (count($matches) == 2)) {
				$versions[] = $matches[1];
			}
		}

		return $versions;
	}

	/**
	 * Renvoi la version de xampp pointé sur le lien symbolique
	 */
	private function getCurentXamppVersion()
	{
		$link = readlink(self::XAMPP);
		$match = preg_match('/lampp(\d\d)/', $link, $matches);
		if (!$match || !is_array($matches) || (count($matches) != 2)) {
			throw new Exception(
				"Erreur avec le liens symbolique de lampp\n".
				"Les dossiers de xampp doivent avoir le chemin ".self::XAMPP.
				" suivi de la version ex: ".self::XAMPP."54"
			, 1);
		}
		$version = $matches[1];

		return $version;
	}

	/**
	 * Change le lien symbolique de xampp
	 */
	private function switchXamppVers($vers)
	{
		$cmd = 'rm -rf '.self::XAMPP;
		$cmdSwitch = 'ln -s '.self::XAMPP.$vers.' '.self::XAMPP;

		echo "Changement de la version ".$this->getCurentXamppVersion()." pour la version ".$vers."\n";
		echo `$cmd`;
		echo `$cmdSwitch`;
	}

	/**
	 * Recupère les versions de xampp installé et valide la vesion passé en params
	 */
	public function __construct($version, $arguments)
	{
		$allVerion = $this->getXamppVersions();
		if (!in_array($version, $allVerion)) {
			throw new Exception("La version ".$version." n'est pas installé", 1);			
		}

		$this->version = $version;
		$this->arguments = $arguments;
	}

	/**
	 * Script
	 */
	public function execute()
	{
		$isXamppStart = $this->isXamppStart();
		$xamppVers = $this->getCurentXamppVersion();

		if ($xamppVers != $this->version) {
			if ($isXamppStart) {
				$this->xampp('stop');
			}

			$this->switchXamppVers($this->version);
		}

		if ($this->arguments != '') $this->xampp($this->arguments);
	}
}