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
use PdfGenerator\Entity\PdfContainer;
use PdfGenerator\Entity\PdfConverter;
use PdfGenerator\Entity\Store;
use PdfGenerator\Hydrator\PdfContainerHydrator;

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
     * @return bool|object|PdfContainer
     *
     * @throws \Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function generateHtml($html, $storeOnFiler = false, $filename = 'file.pdf')
    {
        return $this->sendPdfConverterRequest($filename, $storeOnFiler, PdfConverter::HTML, $html);
    }

    /**
     * @param string $url
     * @param bool $storeOnFiler
     * @param string $filename
     *
     * @return bool|object|PdfContainer
     *
     * @throws Exception
     * @throws \Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function generateUrl($url, $storeOnFiler = false, $filename = 'file.pdf')
    {
        //Check if url contains protocol
        if (strpos(substr($url, 0, 5), 'http') === false) {
            throw new Exception(sprintf('Error : Given URL MUST contain the protocol. Current : %s', $url));
        }

        return $this->sendPdfConverterRequest($filename, $storeOnFiler, PdfConverter::URL, $url);
    }

    /**
     * @param PdfContainer $pdfContainer
     *
     * @return bool|string
     */
    public function getContentsFromPdfContainer(PdfContainer $pdfContainer)
    {
        return file_get_contents($pdfContainer->getUrl());
    }

    /**
     * @param array $data
     *
     * @return object|PdfContainer
     *
     * @throws \Exception
     */
    protected function entityFactory(array $data)
    {
        return (new PdfContainerHydrator())->hydrate($data, new PdfContainer());
    }

    /**
     * @param string $filename
     * @param bool $storeOnFiler
     * @param int $type
     * @param string $data
     *
     * @return bool|object|PdfContainer
     *
     * @throws \Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    protected function sendPdfConverterRequest($filename, $storeOnFiler, $type, $data)
    {
        $request = new RequestDescriptor();

        $serialized = \json_encode(
            [
                'output_filename' => $filename,
                'type' => $type,
                'store' => $storeOnFiler ? Store::FILER: Store::NONE,
                'data' => $data,
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
}
