<?php
/**
 * PdfGeneratorTest.php
 *
 * @date        26/09/17
 * @file        PdfGeneratorTest.php
 */

namespace Tests\PdfGenerator\Client;

use Fei\ApiClient\ResponseDescriptor;
use Fei\ApiClient\Transport\SyncTransportInterface;
use PdfGenerator\Client\Exception;
use PdfGenerator\Client\PdfGenerator;
use PdfGenerator\Entity\PdfConverter;
use PdfGenerator\Entity\Store;
use PHPUnit\Framework\TestCase;

/**
 * Class PdfGeneratorTest
 *
 * @package Tests\PdfGenerator\Client
 */
class PdfGeneratorTest extends TestCase
{
    /**
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function testGenerateHtmlValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn($this->getDefaultResponseDescriptor());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateHtml('<span>Test</span>');

        $this->assertInstanceOf(PdfConverter::class, $response);
    }

    /**
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function testGenerateHtmlNotValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn(true);

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateHtml('<span>Test</span>');

        $this->assertFalse($response);
    }

    /**
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function testGenerateHtmlException()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willThrowException(new \Exception());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $this->expectException(\Exception::class);
        $pdfGenerator->generateHtml('<span>Test</span>');
    }

    /**
     * @throws Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function testGenerateUrlValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn($this->getDefaultResponseDescriptor());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateUrl('http://www.google.fr');

        $this->assertInstanceOf(PdfConverter::class, $response);
    }

    /**
     * @throws Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function testGenerateUrlNotValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn(false);

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateUrl('http://www.google.fr');

        $this->assertFalse($response);
    }

    /**
     * @throws Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function testGenerateUrlException()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willThrowException(new \Exception());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $this->expectException(\Exception::class);
        $pdfGenerator->generateUrl('http://www.google.fr');
    }

    /**
     * @throws Exception
     * @throws \Fei\ApiClient\ApiClientException
     * @throws \Fei\ApiClient\Transport\TransportException
     */
    public function testGenerateUrlNoProtocol()
    {
        $pdfGenerator = new PdfGenerator();

        $this->expectException(\Exception::class);
        $pdfGenerator->generateUrl('www.google.fr');
    }

    /**
     * @return ResponseDescriptor
     */
    public function getDefaultResponseDescriptor()
    {
        $response = new ResponseDescriptor();

        $response->setBody(\json_encode([
            'data' => [
                'data' => 'JVBERi0xLjQKJdPr6eEKMSAwIG9iago8PC9DcmVhdG9yIChDaHJvbWl1bSkKL1Byb2R1Y2VyIChTa2lhL1BERiBtNjEpCi9DcmVhdGlvbkRhdGUgKEQ6MjAxNzA5MjYxNDE0MjMrMDAnMDAnKQovTW9kRGF0ZSAoRDoyMDE3MDkyNjE0MTQyMyswMCcwMCcpPj4KZW5kb2JqCjIgMCBvYmoKPDwvRmlsdGVyIC9GbGF0ZURlY29kZQovTGVuZ3RoIDI4Mz4',
                'type' => PdfConverter::HTML,
                'store' => Store::NONE,
                'download' => false,
                'output_filename' => '/tmp/79cb15e325269c94a6f337397c36cbf9.pdf',
                'category' => 0,
            ]
        ]));

        return $response;
    }
}