<?php

namespace Drupal\azure_openai\Service;

use OpenAI;

class AzureOpenAIService {

  protected $client;
  protected $secret;
  protected $resourceName;
  protected $deploymentId;
  protected $apiKey;
  protected $apiVersion;

  public function __construct() {

    // Prepare secrets
    $this->setSecrets('/var/www/.docksal/secrets.php');

    // Set API Values
    $this->setResourceName($this->getSecret('resourceName'));
    $this->setDeploymentId($this->getSecret('deploymentId'));
    $this->setApiKey($this->getSecret('apiKey'));
    $this->setApiVersion($this->getSecret('apiVersion'));
  }

  public function request($prompt) {
    try {
      // Prepare client
      $this->setClient();

      $result = $this->client->chat()->create([
        'model' => 'gpt-4',
        'messages' => [
          ['role' => 'user', 'content' => $prompt],
        ],
      ]);

      return $result->choices[0]->message->content;
    } catch (\Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
   * Get the value of resourceName
   */ 
  public function getResourceName()
  {
    return $this->resourceName;
  }

  /**
   * Set the value of resourceName
   *
   * @return  self
   */ 
  public function setResourceName($resourceName)
  {
    $this->resourceName = $resourceName;

    return $this;
  }

  /**
   * Get the value of deploymentId
   */ 
  public function getDeploymentId()
  {
    return $this->deploymentId;
  }

  /**
   * Set the value of deploymentId
   *
   * @return  self
   */ 
  public function setDeploymentId($deploymentId)
  {
    $this->deploymentId = $deploymentId;

    return $this;
  }

  /**
   * Get the value of apiKey
   */ 
  public function getApiKey()
  {
    return $this->apiKey;
  }

  /**
   * Set the value of apiKey
   *
   * @return  self
   */ 
  public function setApiKey($apiKey)
  {
    $this->apiKey = $apiKey;

    return $this;
  }

  /**
   * Get the value of apiVersion
   */ 
  public function getApiVersion()
  {
    return $this->apiVersion;
  }

  /**
   * Set the value of apiVersion
   *
   * @return  self
   */ 
  public function setApiVersion($apiVersion)
  {
    $this->apiVersion = $apiVersion;

    return $this;
  }

  /**
   * Get the value of client
   */ 
  public function getClient()
  {
    return $this->client;
  }

  /**
   * Set the value of client
   *
   * @return  self
   */ 
  public function setClient()
  {
    $resourceName = $this->getResourceName();
    $deploymentId = $this->getDeploymentId();
    $apiKey = $this->getApiKey();
    $apiVersion = $this->getApiVersion();

    // Guard
    if (!$resourceName || !$deploymentId || !$apiKey || !$apiVersion)
    {
      throw new \Exception('Unable to configure client. Secrets file is not set up properly.');
    }

    // Construct the OpenAI client
    $this->client = OpenAI::factory()
      ->withBaseUri("https://$resourceName.openai.azure.com/openai/deployments/$deploymentId")
      ->withHttpHeader('api-key', $apiKey)
      ->withQueryParam('api-version', $apiVersion)
      ->make();

    return $this;
  }

  /**
   * Get the value of secrets
   */ 
  public function getSecret($key)
  {
    return $this->secret[$key];
  }

  /**
   * Set the value of secrets
   *
   * @return  self
   */ 
  public function setSecrets($secret)
  {
    if (!file_exists($secret) || filesize($secret) <= 3) {
      throw new \Exception('Unable to locate secrets file.');
    }

    try {
      $this->secret = require $secret;
    } catch (\Exception $e) {
      return 'Error: ' . $e->getMessage();
    }

    return $this;
  }
}

