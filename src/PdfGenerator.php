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
use PdfGenerator\Entity\PdfConverter;
use PdfGenerator\Entity\Store;
use PdfGenerator\Hydrator\PdfConverterHydrator;

/**
 * PdfGenerator
 */
class PdfGenerator extends AbstractApiClient
{
    /**
     * @param string $html
     * @param bool $storeOnFiler
     * @param string $filename
     *
     * @return bool|PdfConverter
     *
     * @throws \Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function generateHtml($html, $storeOnFiler = false, $filename = 'file.pdf')
    {
        $request = new RequestDescriptor();

        $serialized = \json_encode(
            [
            'output_filename' => $filename,
            'type' =>  PdfConverter::HTML,
            'store' => $storeOnFiler ? Store::FILER: Store::NONE,
            'data' => $html,
            ]
        );

        $request->addBodyParam('convert', $serialized);
        $request->setUrl($this->buildUrl('/api/pdf-generator/convert'));
        $request->setMethod('POST');

        $response = $this->send($request);

        if ($response instanceof ResponseDescriptor) {
            $data = $response->getData();
            $entity = $this->entityFactory($data);

            return $entity;
        }

        return false;
    }

    /**
     * @param string $url
     * @param bool $storeOnFiler
     * @param string $filename
     *
     * @return bool|PdfConverter
     * @throws Exception
     *
     * @throws \Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function generateUrl($url, $storeOnFiler = false, $filename = 'file.pdf')
    {
        $request = new RequestDescriptor();

        //Check if url contains protocol
        if (strpos(substr($url, 0, 5), 'http') === false) {
            throw new Exception(sprintf('Error : Given URL MUST contain the protocol. Current : %s', $url));
        }

        $serialized = \json_encode(
            [
            'output_filename' => $filename,
            'type' =>  PdfConverter::HTML,
            'store' => $storeOnFiler ? Store::FILER: Store::NONE,
            'url' => $url,
            ]
        );

        $request->addBodyParam('convert', $serialized);
        $request->setUrl($this->buildUrl('/api/pdf-generator/convert'));
        $request->setMethod('POST');

        $response = $this->send($request);

        if ($response instanceof ResponseDescriptor) {
            $data = $response->getData();
            $entity = $this->entityFactory($data);

            return $entity;
        }

        return false;
    }

    /**
     * @param array $data
     *
     * @return PdfConverter
     *
     * @throws \Exception
     */
    public function entityFactory(array $data)
    {
        return (new PdfConverterHydrator())->hydrate($data, new PdfConverter());
    }
}
