<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context {

  private $driver;
  private $session;

  private $roundcubeUrl;
  private $username;
  private $password;


  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
    $this->driver = new \Behat\Mink\Driver\GoutteDriver();
    $this->session = new \Behat\Mink\Session($this->driver);
    $this->session->start();
  }


  /**
   * @Given there is an roundcube server at :url
   */
  public function thereIsAnRoundcubeServerAt($url) {
    $this->roundcubeUrl = $url;
  }

  /**
   * @Given there is an account :username with the password :password
   */
  public function thereIsAnAccountWithThePassword($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }

  /**
   * @When I log in
   */
  public function iLogIn() {
    $url = $this->roundcubeUrl . '?_task=login';

    $this->session->visit($url);
    $page = $this->session->getPage();
    $token = $page->find('css', 'input[name=_token]');
    $token = $token->getAttribute('value');

    $post = [
      '_token' => $token,
      '_task' => 'login',
      '_action' => 'login',
      '_timezone' => 'Europe/Amsterdam',
      '_url' => '_task=login',
      '_user' => $this->username,
      '_pass' => $this->password
    ];

    $this->session->getDriver()->getClient()->request('POST', $url, $post);

  }

  /**
   * @When I go to task :task action :action
   */
  public function iGoToTaskAction($task, $action) {
    $url = $this->roundcubeUrl . '?_task=' . $task . '&_action=' . $action;
    $this->session->visit($url);
  }


  /**
   * @Then I should get a :spec http response
   */
  public function iShouldGetAHttpResponse($spec) {
    $status = (int) $this->session->getStatusCode();
    if (strpos($spec, 'x') !== FALSE) {
      switch ($spec) {
        case '2xx':
          if ($status < 200 || $status > 299) {
            throw new Exception('Invalid status code ' . $status);
          }
          break;

        case '3xx':
          if ($status < 300 || $status > 399) {
            throw new Exception('Invalid status code ' . $status);
          }
          break;

        case '4xx':
          if ($status < 400 || $status > 499) {
            throw new Exception('Invalid status code ' . $status);
          }
          break;

        case '5xx':
          if ($status < 500 || $status > 599) {
            throw new Exception('Invalid status code ' . $status);
          }
          break;

        default:
          throw new PendingException();
      }
    }
    else {
      if ($status != $spec) {
        throw new Exception('Invalid status code ' . $status);
      }
    }
  }

  /**
   * @Then I should have the element :selector
   */
  public function iShouldHaveTheElement($selector) {
    $page = $this->session->getPage();
    $element = $page->find('css', $selector);

    file_put_contents(__DIR__ . '/test.html', $page->getContent());

    if ($element === NULL) {
      throw new Exception('Element not found');
    }
  }

  /**
   * @When I submit the email page with the information:
   */
  public function iSubmitTheEmailPageWithTheInformation(TableNode $table) {

    $page = $this->session->getPage();

    $parameter = [
      'cc' => '',
      'bcc' => '',
      'reply to' => '',
      'subject' => ''
    ];
    // Parse the parameter table
    foreach ($table->getColumnsHash() as $row) {
      $parameter[$row['field']] = $row['value'];
    }


    // Get the hidden roundcube form information
    $html = $page->getHtml();
    $re = '/"identities":{"([^"]+)"/';
    preg_match($re, $html, $matches);
    $sender = $matches[1];
    $re = '/"compose_id":"([^"]+)"/';
    preg_match($re, $html, $matches);
    $compose_id = $matches[1];

    $token = $page->find('css', 'input[name=_token]');
    $token = $token->getAttribute('value');


    // Rebuild the form submission
    $post = [
      '_token' => $token,
      '_task' => 'mail',
      '_action' => 'send',
      '_id' => $compose_id,
      '_attachments' => '',
      '_from' => $sender,
      '_to' => $parameter['recipient'],
      '_cc' => $parameter['cc'],
      '_bcc' => $parameter['bcc'],
      '_replyto' => $parameter['reply to'],
      '_subject' => $parameter['subject'],
      '_priority' => 0,
      '_store_target' => 'INBOX.Sent',
      '_draft_saveid' => '',
      '_draft' => '',
      '_is_html' => 0,
      '_framed' => 1,
      '_message' => $parameter['body']
    ];

    $this->session->getDriver()
      ->getClient()
      ->request('POST', $this->roundcubeUrl . '?_task=mail', $post);

  }


}
