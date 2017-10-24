<?php
/**
 * PdfGenerator.php
 *
 * @date        25/09/17
 * @file        PdfGenerator.php
 */

namespace PdfGenerator\Client;

use Fei\ApiClient\AbstractApiClient;
use Fei\ApiClient\RequestDescriptor;
use Fei\ApiClient\ResponseDescriptor;
use Fei\Entity\EntityInterface;

/**
 * PdfGenerator
 */
class PdfGenerator extends AbstractApiClient
{
    /**
     * @param string $html
     *
     * @return bool|EntityInterface
     */
    public function generateHtml($html)
    {
        $request = new RequestDescriptor();

        $request->addBodyParam('html', $html);

        $request->setUrl($this->buildUrl('/api/pdf-generator/html'));
        $request->setMethod('POST');

        try {
            $response = $this->send($request);

            if ($response instanceof ResponseDescriptor) {
                $meta = $response->getMeta('entity');
                $data = $response->getData();
                $entity = $this->entityFactory($meta, $data);

                return $entity;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * @param string $url
     *
     * @return bool|EntityInterface
     * @throws Exception
     */
    public function generateUrl($url)
    {
        $request = new RequestDescriptor();

        //Check if url contains protocol
        if (strpos(substr($url, 0, 5), 'http') === false) {
            throw new Exception(sprintf('Error : Given URL MUST contain the protocol. Current : %s', $url));
        }

        $request->addBodyParam('url', $url);
        $request->setUrl($this->buildUrl('/api/pdf-generator/url'));
        $request->setMethod('POST');

        try {
            $response = $this->send($request);

            if ($response instanceof ResponseDescriptor) {
                $meta = $response->getMeta('entity');
                $data = $response->getData();
                $entity = $this->entityFactory($meta, $data);

                return $entity;
            }
        } catch (\Exception $e) {
            var_dump($e);
        }

        return false;
    }

    /**
     * @param       $targetEntity
     * @param array $data
     *
     * @return EntityInterface
     */
    public function entityFactory($targetEntity, array $data)
    {
        /** @var EntityInterface $entity */
        $entity = new $targetEntity;

        $entity->hydrate($data);

        return $entity;
    }
}