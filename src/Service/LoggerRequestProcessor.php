<?php

namespace CreamIO\BaseBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class LoggerRequestProcessor
{
    /**
     * @var RequestStack Injected request stack
     */
    protected $request;

    /**
     * RequestProcessor constructor.
     *
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     * Add extra information to log from request.
     *
     * @param array $record Log object array.
     *
     * @return array
     */
    public function processRecord(array $record): array
    {
        $req = $this->request->getCurrentRequest();
        $record['extra']['client_ip']       = $req->getClientIp();
        $record['extra']['client_port']     = $req->getPort();
        $record['extra']['uri']             = $req->getUri();
        $record['extra']['query_string']    = $req->getQueryString();
        $record['extra']['method']          = $req->getMethod();
        $record['extra']['request']         = $req->request->all();

        return $record;
    }
}