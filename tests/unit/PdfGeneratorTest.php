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
use PdfGenerator\Entity\PdfContainer;

/**
 * PdfGeneratorTest
 */
class PdfGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateHtmlValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn($this->getDefaultResponseDescriptor());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateHtml('<span>Test</span>');

        $this->assertInstanceOf(PdfContainer::class, $response);
    }

    public function testGenerateHtmlNotValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn(true);

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateHtml('<span>Test</span>');

        $this->assertFalse($response);
    }

    public function testGenerateHtmlException()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willThrowException(new \Exception());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateHtml('<span>Test</span>');

        $this->assertFalse($response);
    }

    public function testGenerateUrlValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn($this->getDefaultResponseDescriptor());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateUrl('http://www.google.fr');

        $this->assertInstanceOf(PdfContainer::class, $response);
    }

    public function testGenerateUrlNotValid()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willReturn(false);

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateUrl('http://www.google.fr');

        $this->assertFalse($response);
    }

    public function testGenerateUrlException()
    {
        $transport = $this->getMockBuilder(SyncTransportInterface::class)->getMock();
        $transport->expects($this->once())->method('send')->willThrowException(new \Exception());

        $pdfGenerator = new PdfGenerator();

        $pdfGenerator->setTransport($transport);

        $response = $pdfGenerator->generateUrl('http://www.google.fr');

        $this->assertFalse($response);
    }

    /**
     * @expectedException Exception
     */
    public function testGenerateUrlNoProtocol()
    {
        $pdfGenerator = new PdfGenerator();
        $pdfGenerator->generateUrl('www.google.fr');
    }

    /**
     * @return ResponseDescriptor
     */
    public function getDefaultResponseDescriptor()
    {
        $response = new ResponseDescriptor();

        $response->setBody(\json_encode([
            'meta' => [
                'entity' => PdfContainer::class
            ],
            'data' => [
                'data' => 'JVBERi0xLjQKJdPr6eEKMSAwIG9iago8PC9DcmVhdG9yIChDaHJvbWl1bSkKL1Byb2R1Y2VyIChTa2lhL1BERiBtNjEpCi9DcmVhdGlvbkRhdGUgKEQ6MjAxNzA5MjYxNDE0MjMrMDAnMDAnKQovTW9kRGF0ZSAoRDoyMDE3MDkyNjE0MTQyMyswMCcwMCcpPj4KZW5kb2JqCjIgMCBvYmoKPDwvRmlsdGVyIC9GbGF0ZURlY29kZQovTGVuZ3RoIDI4Mz4',
                'originName' => '/tmp/79cb15e325269c94a6f337397c36cbf9.pdf'
            ]
        ]));

        return $response;
    }
}