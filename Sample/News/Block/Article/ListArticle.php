<?php
/**
 * Sample_News extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category       Sample
 * @package        Sample_News
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
namespace Sample\News\Block\Article;

use Sample\News\Model\Resource\Article\CollectionFactory;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class ListArticle extends Template {
    /**
     * @var \Sample\News\Model\Resource\Article\CollectionFactory
     */
    protected $_articleCollectionFactory;
    /**
     * @var \Magento\Framework\UrlFactory
     */
    protected $_urlFactory;


    /**
     * @access public
     * @param Context $context
     * @param CollectionFactory $articleCollectionFactory
     * @param UrlFactory $urlFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $articleCollectionFactory,
        UrlFactory $urlFactory,
        array $data = []
    ) {
        $this->_articleCollectionFactory = $articleCollectionFactory;
        $this->_urlFactory = $urlFactory;
        parent::__construct($context, $data);
    }

    /**
     * @access protected
     */
    protected  function _construct() {
        parent::_construct();
        $articles = $this->_articleCollectionFactory->create()->addFieldToSelect('*')
            ->addFieldToFilter('status', 1)
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('title','desc');
        $this->setArticles($articles);
    }
    public function isRssCatalogEnable() {
        $this->_scopeConfig->getValue('sample_news/article/rss', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @access protected
     * @return $this
     */
    protected function _prepareLayout() {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'sample_news.article.list.pager')
            ->setCollection($this->getArticles());
        $this->setChild('pager', $pager);
        $this->getArticles()->load();
        return $this;
    }

    /**
     * @access public
     * @return string
     */
    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }
}