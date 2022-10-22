<?php
/**
 * Copyright © Epaard, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Epaard\Whatsapp\Block\Product;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Product Info block
 */
class Info extends \Magento\Catalog\Block\Product\View
{
    const WHATSAPP_LOGO = "whatsapp/general/logo";
    const WHATSAPP_TEXT = "whatsapp/general/chat_text";
    const WHATSAPP_PHONE_NUMBER = "whatsapp/general/number";
    const WHATSAPP_URL = "https://wa.me/{number}/?text={text}";
    const SEARCH = ['{number}', '{text}'];

    protected $scopeConfig;
    /**
     * @param Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface|\Magento\Framework\Pricing\PriceCurrencyInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     * @codingStandardsIgnoreStart
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->_productHelper = $productHelper;
        $this->urlEncoder = $urlEncoder;
        $this->_jsonEncoder = $jsonEncoder;
        $this->productTypeConfig = $productTypeConfig;
        $this->string = $string;
        $this->_localeFormat = $localeFormat;
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->priceCurrency = $priceCurrency;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    /**
     * Retrieve Number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->getStoreConfig(self::WHATSAPP_PHONE_NUMBER);
    }

    /**
     * Retrieve Number
     *
     * @return string
     */
    public function getText()
    {
        
        $number = $this->getNumber();
        $text = $this->getStoreConfig(self::WHATSAPP_TEXT);
        $productUrl = $this->_productHelper->getProductUrl($this->getProduct());
        $text = implode(" ", [$text, $productUrl]);

        if($number && $text)
        {
            $replace = [$number, urlencode($text)];
            $url = str_replace(self::SEARCH, $replace, self::WHATSAPP_URL);
            return $url;
        }
        return false;
    }

    /**
     * Retrieve logo Url
     *
     * @return string
     */
    public function getLogo()
    {
        $path = $this->getStoreConfig(self::WHATSAPP_LOGO);
        $logoUrl = $this->_urlBuilder
                ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) ."/logo/". $path;
        return $logoUrl;
    }

    /**
     * Retrieve Store Config Value
     *
     * @return string
     */
    protected function getStoreConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}