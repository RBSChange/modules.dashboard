<?php
/**
 * dashboard_BlockDashboardRssReaderAction
 * @package modules.dashboard.lib.blocks
 */
class dashboard_BlockDashboardRssReaderAction extends dashboard_BlockDashboardAction
{
	
	private $title = null;
	
	/**
	 * @see dashboard_BaseModuleAction::getTitle()
	 * @return string
	 */
	public function getTitle()
	{
		return ($this->title === null) ? parent::getTitle() : $this->title;
	}
	
	/**
	 * @return boolean
	 */
	public function getOpenModule()
	{
		return false;
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @param boolean $forEdition
	 */
	protected function setRequestContent($request, $forEdition)
	{
		$items = array();
		$feedurl = $this->getConfiguration()->getFeedurl();
		if (f_util_StringUtils::isEmpty($feedurl))
		{
			$this->title = f_Locale::translateUI('&modules.dashboard.bo.general.No-Feed-URL-define;');
		}
		else if ($request->hasParameter('refresh'))
		{
			
			try
			{
				session_commit();
				$rssRequest = HTTPClientService::getInstance()->getNewHTTPClient();
				$rssRequest->setTimeOut(15);
				$rssRequest->setReferer(LinkHelper::getUIChromeActionLink('uixul', 'Admin') . '&fqdn=' . urlencode(Framework::getUIDefaultHost()));
				$xmlRSS = $rssRequest->get($this->getConfiguration()->getFeedurl());
				session_start();
				$doc = f_util_DOMUtils::fromString($xmlRSS);			
				$chanel = $doc->getElementsByTagName('channel')->item(0);
				if ($chanel !== null)
				{
					$title = $chanel->getElementsByTagName('title')->item(0);
					if ($title !== null)
					{
						$this->title = $title->textContent;
					}
					
					if (!$forEdition)
					{
						foreach ($chanel->getElementsByTagName('item') as $index => $nodeItem)
						{
							if ($index >= $this->getConfiguration()->getMaxitemcount())
							{
								break;
							}
							$items[] = $this->getItemInfo($nodeItem);
						}
					}
				}
				else
				{
					$this->title = f_Locale::translateUI('&modules.dashboard.bo.general.Feed-error;') . ': ' . $this->getConfiguration()->getFeedurl();
				}
			}
			catch (Exception $e)
			{
				Framework::exception($e);
				$this->title = f_Locale::translateUI('&modules.dashboard.bo.general.Feed-error;') . ': ' . $this->getConfiguration()->getFeedurl();
			}
		}
		
		$request->setAttribute('title', htmlspecialchars($this->getTitle(), ENT_COMPAT, "UTF-8"));
		$request->setAttribute('items', $items);
	}
	
	private function getItemInfo($item)
	{
		$infos = array();
		
		$title = $item->getElementsByTagName('title')->item(0);
		if ($title !== null)
		{
			$infos['title'] = $title->textContent;
		}
		
		$link = $item->getElementsByTagName('link')->item(0);
		if ($link === null)
		{
			$link = $item->getElementsByTagName('guid')->item(0);
		}
		if ($link !== null)
		{
			$infos['permalink'] = trim($link->textContent);
		}
		
		$date = $item->getElementsByTagName('pubDate')->item(0);
		if ($date !== null)
		{
			$infos['date'] = $this->parseRSSDate($date->textContent);
		}
		
		return $infos;
	}
	
	/**
	 * @param String $string
	 * @return String
	 */
	private function parseRSSDate($string)
	{
		$terms = explode(' ', trim($string));
		if (! is_numeric($terms[0]))
		{
			$terms = array_slice($terms, 1);
		}
		$day = intval($terms[0]);
		$month = array_search($terms[1], array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec")) + 1;
		$year = $terms[2];
		list ($hour, $minute, $second) = explode(':', $terms[3] . ':00');
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		return date('Y-m-d H:i:s', $timestamp);
	}
}
