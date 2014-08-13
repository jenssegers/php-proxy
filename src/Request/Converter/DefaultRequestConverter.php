<?php
namespace Phpproxy\Request\Converter;


use GuzzleHttp\Message\MessageFactory;
use GuzzleHttp\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultRequestConverter implements RequestConverterInterface
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    public function __construct()
    {
        $this->messageFactory = new MessageFactory();
    }

    /**
     * @param Request $request
     * @return RequestInterface
     */
    public function convert(Request $request)
    {
        return $this->messageFactory->fromMessage((string) $request);
    }
}
