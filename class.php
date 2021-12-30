<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;


class iplogicInstagramRecent extends CBitrixComponent
{

	protected $errors = [];


	function __construct($component = null) {
		parent::__construct($component);
	}


	function onPrepareComponentParams($arParams) {
		return $arParams;
	}


	function executeComponent() {
		$this->prepareResult();
		if (count($this->errors))
		{
			$this->printErrors();
			return;
		}

		$this->includeComponentTemplate();
	}


	protected function printErrors()
	{
		if ($this->arParams['SHOW_ERRORS'] != "Y")
			return;
		foreach ($this->errors as $error) {
			ShowError($error);
		}
	}


	protected function prepareResult() {
		if (count($this->errors))
			return;
		if( $this->arParams["EXPANDED"] != "Y" ) {
			$this->getСollapsed();
		}
		else {
			$this->getExpanded();
		}
	}


	protected function getСollapsed() {
		$p = "me/media?fields=id,media_type,media_url,thumbnail_url,permalink,children" . 
			"&limit=" . $this->arParams["NUM"] . "&access_token=" . $this->getAccessToken();

		if(count($this->errors)) {
			return;
		}

		$result = $this->_call($p);

		//echo "<pre>"; print_r($result); echo "</pre>";

		if($result === null) {
			$this->errors[] = "Unacceptable JSON response";
			return;
		}
		if (isset($result["error"])) {
			$this->errors[] = $result["error"]["message"];
			return;
		}

		$this->arResult['ITEMS'] = [];
		if (count($result["data"])){
			$i = 1;
			foreach($result["data"] as $post) {
				$this->arResult['ITEMS'][] = [
					"IMG" => [
						"TRUM" 		=> $post["thumbnail_url"],
						"STANDART" 	=> $post["media_url"],
					],
					"URL" => $post["permalink"],
					"TYPE" => $post["media_type"],
				];
				$i++;
				if($i>$this->arParams["NUM"])
					break;
			}
		}
	}


	protected function getExpanded()
	{
		$p = "me/media?fields=id,media_type,media_url,thumbnail_url,permalink,children" . 
			"{fields=id,media_type,media_url,thumbnail_url,permalink}" . 
			"&limit=" . $this->arParams["NUM"] . "&access_token=" . $this->getAccessToken();

		if(count($this->errors)) {
			return;
		}

		$result = $this->_call($p);

		//echo "<pre>"; print_r($result); echo "</pre>";

		if($result === null) {
			$this->errors[] = "Unacceptable JSON response";
			return;
		}
		if (isset($result["error"])) {
			$this->errors[] = $result["error"]["message"];
			return;
		}

		$this->arResult['ITEMS'] = [];
		if (count($result["data"])){
			$i = 1;
			foreach($result["data"] as $post) {
				if(!empty($post["children"]["data"])) {
					foreach($post["children"]["data"] as $child) {
						$this->arResult['ITEMS'][] = [
							"IMG" => [
								"TRUM" 		=> $child["thumbnail_url"],
								"STANDART" 	=> $child["media_url"],
							],
							"URL" => $child["permalink"],
							"TYPE" => $child["media_type"],
						];
						$i++;
						if($i>$this->arParams["NUM"])
							return;
					}
				}
				else {
					$this->arResult['ITEMS'][] = [
						"IMG" => [
							"TRUM" 		=> $post["thumbnail_url"],
							"STANDART" 	=> $post["media_url"],
						],
						"URL" => $post["permalink"],
						"TYPE" => $post["media_type"],
					];
					$i++;
					if($i>$this->arParams["NUM"])
						return;
				}
			}
		}

	}


	protected function getAccessToken()
	{
		$accessToken = Option::get("iplogic", "ipl_inst_".$this->arParams["ID"]."_token", "");
		$tokenDate = Option::get("iplogic", "ipl_inst_".$this->arParams["ID"]."_time");

		$oldToken = "";

		if($accessToken != "") {
			if ($tokenDate > 0) {
				$dayDiff = (time() - $tokenDate) / 86400;
				if ($dayDiff <= 50) {
					return $accessToken;
				}
				if ($dayDiff > 50 && $dayDiff < 60) {
					$oldToken = $accessToken;
				}
			}
		}

		if ($oldToken == "") {
			$oldToken = $this->arParams["ACCESS_TOKEN"];
		}

		$p = "refresh_access_token?grant_type=ig_refresh_token&access_token=" . $oldToken;

		$result = $this->_call($p);

		if(isset($result["access_token"])) {
			Option::set("iplogic", "ipl_inst_".$this->arParams["ID"]."_token", $result["access_token"]);
			Option::set("iplogic", "ipl_inst_".$this->arParams["ID"]."_time", time());
			return $result["access_token"];
		}

		$this->errors[] = "Can't get access token";
	}


	protected function _call($params)
	{
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, "https://graph.instagram.com/" . $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		return @json_decode($result,true);
	}

}