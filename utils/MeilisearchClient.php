<?php

namespace Utils;

use Constants\MeilisearchConfig;
use Meilisearch\Client;

class MeilisearchClient {

    private $meilisearchClient;
    private $index;

    public function __construct(string $indexName) { 
        $this->meilisearchClient = new Client(MeilisearchConfig::getUrl(), MeilisearchConfig::getApiKey());
        $this->index = $this->meilisearchClient->index($indexName);
    }

    public function addDocuments(array $documents) {
        $this->index->addDocuments($documents);
    }

    public function deleteDocument(string $documentId) {
        $this->index->deleteDocument($documentId);
    }
}
