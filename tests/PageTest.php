<?php declare(strict_types=1);
  require 'vendor/autoload.php';
//  require "config.php";

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class PageTest extends TestCase
{
    private $db;
    private $client;

    public function setUp(): void
    {
      $this->db = "test.csv";
      // clear out any old data
      $this->clearData();
      $this->client = new GuzzleHttp\Client(['cookies' => true]);
      $this->client->get('https://guestbook.briantoone.repl.co/test.php');
    }

    private function clearData(): void
    {
      echo "deleting {$this->db}!";
      @unlink($this->db);   
    }

    private $testdata = [
      "Test person,test comment",
      "Another person,a longer comment to be sure"
    ];

    // load our test "fixtures" i.e., the data that we can test against
    private function initData(): void
    {
      file_put_contents($this->db,implode("\n",$this->testdata));
    }
  
    // look for the text that must always appear on the page
    public function testRequiredTextNoEntries(): void
    {
      // Create a client with a base URI
      $response = $this->client->get('https://guestbook.briantoone.repl.co/');
      $body = $response->getBody();
      // Implicitly cast the body to a string and echo it
      // echo $body;
      // Explicitly cast the body to a string
      $stringBody = (string) $body;
      $this->assertStringContainsString("GuestBook", $stringBody);
      $this->assertStringContainsString("Name", $stringBody);
      $this->assertStringContainsString("Comment", $stringBody);
      $this->assertStringContainsString("View our guestbook below", $stringBody);
      $this->assertStringContainsString("There are no current entries", $stringBody);
      $gbi = strpos($stringBody, "GuestBook");
      $ni = strpos($stringBody, "Name");
      $ci = strpos($stringBody, "Comment");
      $vgbi = strpos($stringBody, "View our guestbook below");
      $this->assertLessThan($ni, $gbi);
      $this->assertLessThan($ci, $ni);
      $this->assertLessThan($vgbi, $ci);
    }

    public function testRequiredTextWithEntries(): void
    {
      $this->initData();
    
      $response = $this->client->get('https://guestbook.briantoone.repl.co/');
      $body = $response->getBody();
      // Implicitly cast the body to a string and echo it
      //echo $body;
      // Explicitly cast the body to a string
      $stringBody = (string) $body;
      $this->assertStringContainsString("GuestBook", $stringBody);
      $this->assertStringContainsString("Name", $stringBody);
      $this->assertStringContainsString("Comment", $stringBody);
      $this->assertStringContainsString("View our guestbook below", $stringBody);
      foreach ($this->testdata as $data) {
        $this->assertStringContainsString(str_replace(",",": ", $data), $stringBody);
      }
      $gbi = strpos($stringBody, "GuestBook");
      $ni = strpos($stringBody, "Name");
      $ci = strpos($stringBody, "Comment");
      $vgbi = strpos($stringBody, "View our guestbook below");
      $this->assertLessThan($ni, $gbi);
      $this->assertLessThan($ci, $ni);
      $this->assertLessThan($vgbi, $ci);
    }

    // look for the text that must always appear on the page
    public function testSubmission(): void
    {
      // Create a client with a base URI
      $response = $this->client->request('POST', 
        'https://guestbook.briantoone.repl.co/process.php', [
        'allow_redirects' => false,
        'form_params' => [
          'name' => 'Test user',
          'comment' => 'this is a test comment',
        ]
      ]);
      echo $response->getStatusCode();
      $this->assertSame("302", (string)$response->getStatusCode());

      // Verify that the response redirects us to the index page displaying a message
      $this->clearData();
      $response = $this->client->request('POST', 
        'https://guestbook.briantoone.repl.co/process.php', [
        'form_params' => [
          'name' => 'Test user',
          'comment' => 'this is a test comment',
        ]
      ]);
      $body = $response->getBody();
      $stringBody = (string) $body;
    }

    public function testDelete() {
      $response = $this->client->get('https://guestbook.briantoone.repl.co/delete.php');
      $body = $response->getBody();
      $stringBody = (string) $body;
      // this makes sure the delete page itself returns nothing
      $this->assertSame("", $stringBody);
      
      // verify that the test database has been cleared completely
      $this->assertEquals("", file_get_contents($this->db));

      // make sure that the index page is back to saying there are no entries
      $response = $this->client->get('https://guestbook.briantoone.repl.co/');
      $body = $response->getBody();
      $stringBody = (string) $body;
      $this->assertStringContainsString("There are no current entries", $stringBody);
    }
}