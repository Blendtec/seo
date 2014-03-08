<?php
/**
 * Seo Helper, handles title tags and meta tags
 * @author Nick Baker <nick@webtechnick.com>
 * @since 4.5
 * @license MIT
 */
App::uses('SeoUtil', 'Seo.Lib');
App::uses('SeoUri', 'Seo.Model');
class SeoHelper extends AppHelper {

	public $helpers = array('Html');

	public $SeoMetaTag = null;

	public $SeoTitle = null;

	public $SeoCanonical = null;

	public $SeoABTest = null;

	public $honeyPotId = 1;

/**
 * Show the meta tags designated for this uri
 * @param array of name => content meta tags to merge with giving priority to SEO meta tags
 * @return string of meta tags to show.
 */
	public function metaTags($metaData = array()) {
		$this->loadModel('SeoMetaTag');
		$request = env('REQUEST_URI');
		$metaTags = $this->SeoMetaTag->findAllTagsByUri($request);
		$retval = "";

		foreach ($metaTags as $tag) {
			if (isset($metaData[$tag['SeoMetaTag']['name']])) {
				unset($metaData[$tag['SeoMetaTag']['name']]);
			}
			$data = array();
			if ($tag['SeoMetaTag']['is_http_equiv']) {
				$data['http-equiv'] = $tag['SeoMetaTag']['name'];
			} else {
				$data['name'] = $tag['SeoMetaTag']['name'];
			}
			$data['content'] = $tag['SeoMetaTag']['content'];
			$retval .= $this->Html->meta($data);
		}
		if (!empty($metaData)) {
			foreach ($metaData as $name => $content) {
				$retval .= $this->Html->meta(array('name' => $name, 'content' => $content));
			}
		}
		return $retval;
	}

/**
 * Return a canonical link tag for SEO purpolses
 * Utility method
 * @param router friendly URL
 * @param boolean full url or relative (default true)
 * @return HTMlElement of canonical link or empty string if none found/used
 */
	public function canonical($url = null, $full = true) {
		if ($url === null) {
			$this->loadModel('SeoCanonical');
			$request = env('REQUEST_URI');
			$url = $this->SeoCanonical->findByUri($request);
		}

		if ($url) {
			$path = Router::url($url, $full);
			return $this->Html->tag('link', null, array('rel' => 'canonical', 'href' => $path));
		}
		return "";
	}

/**
 * Show a honeypot link
 * to bait scrappers to click on for autobanning
 * @param string title for link
 * @param array of options
 * @return HtmlLink to honeypot action
 */
	public function honeyPot($title = 'Click Here', $options = array()) {
		$options = array_merge(
			array(
				'rel' => 'nofollow',
				'id' => 'honeypot-' . $this->nextId()
			),
			$options
		);

		$link = $this->Html->link(
			$title,
			SeoUtil::getConfig('honeyPot'),
			$options
		);

		$javascript = $this->Html->scriptBlock("
			document.getElementById('{$options['id']}').style.display = 'none';
			document.getElementById('{$options['id']}').style.zIndex = -1;
		");

		return $link . $javascript;
	}

/**
 * Find the title tag related to this request and output the result.
 * @param string default title tag
 * @return string title for requested uri
 */
	public function title($default = "") {
		$this->loadModel('SeoTitle');
		$request = env('REQUEST_URI');
		$seoTitle = $this->SeoTitle->findTitleByUri($request);
		$title = $seoTitle ? $seoTitle['SeoTitle']['title'] : $default;
		return $this->Html->tag('title', $title);
	}

/**
 * Load a plugin model 
 * @param string modelname
 * @return void
 */
	public function loadModel($model = null) {
		if ($model && $this->$model == null) {
			App::import('Model', "Seo.$model");
			$this->$model = ClassRegistry::init("Seo.$model");
		}
	}

/**
 * Return the next Id to show.
 */
	public function nextId() {
		return $this->honeyPotId++;
	}

/**
 * Return the ABTest GA code on current request
 * @param mixed test to show code for (if null, will check the View for ABTest publiciable and use that.
 * @param array of options
 *  - publicname the publiciable named of the legacy pageTracker publiciable (default pageTracker). Only used when legacy is turn on in config
 *  - scriptBlock -- boolean if true will return scriptBlock of javascript (default false)
 * @return string ga script test, or null
 */
	public function getABTestJS($test = null, $options = array()) {
		if (!$test) {
			if (isset($this->_View->viewpublics['ABTest']) && $this->_View->viewpublics['ABTest']) {
				$test = $this->_View->viewpublics['ABTest'];
			}
		}
		$options = array_merge(array(
			'publicname' => 'pageTracker',
			'scriptBlock' => false,
			), (array)$options
		);
		if ($test && isset($test['SeoABTest']['slug'])) {
			$category = SeoUtil::getConfig('abTesting.category');
			$scope = SeoUtil::getConfig('abTesting.scope');
			$slot = SeoUtil::getConfig('abTesting.slot');
			if (SeoUtil::getConfig('abTesting.legacy')) {
				$retval = "{$options['publicname']}._setCustompublic($slot,'$category','{$test['SeoABTest']['slug']}',$scope);";
			} else {
				$retval = "_gaq.push(['_setCustompublic',$slot,'$category','{$test['SeoABTest']['slug']}',$scope]);";
			}
			if ($options['scriptBlock']) {
				return $this->Html->scriptBlock($retval);
			}
			return $retval;
		}
		return null;
	}

	public function redmineLink($ticketId = null) {
		if ($ticketId) {
			return $this->Html->link($ticketId, SeoUtil::getConfig('abTesting.redmine') . $ticketId, array('class' => 'btn btn-mini btn-info', 'target' => '_blank'));
		}
		return null;
	}

}