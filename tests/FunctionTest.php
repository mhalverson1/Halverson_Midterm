<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

require "functions.php";

final class FunctionTest extends TestCase {

    private $db;

    public function setUp(): void
    {
      $this->db = "test.csv";
      // clear out any old data
      $this->clearData();
    }

    public function testPushAndPop(): void
    {
        $stack = [];
        $this->assertSame(0, count($stack));

        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[0]);
        $this->assertSame(1, count($stack));

        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
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

    private function clearData(): void
    {
      echo "deleting {$this->db}!";
      @unlink($this->db);   
    }

    /**
     * Call the deleteFunction with no parameters
     * Expected result: the guestbook is deleted ... 
     */
    public function testDelete(): void {
      global $config;
      $this->initData();
      $config['db'] = $this->db;
      deleteGuestbook(); // make the function call that we are testing
      $this->assertEquals("", file_get_contents($config['db']));
    }

    public function testAddGuestbookEntry(): void {
      global $config;
      $this->clearData();
      $config['db'] = $this->db;
      foreach ($this->testdata as $data) {
        $parts = explode(",", $data);
        addGuestbookEntry($parts[0], $parts[1]); // make the function call that we are testing
        $result = file_get_contents($config['db']);
        echo $result;
        $this->assertStringContainsString('"'.$parts[0].'","'.$parts[1].'"', $result);
      }
    }
}