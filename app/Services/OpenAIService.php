<?php

namespace App\Services;

use App\Facades\ElasticsearchFacade;
use App\Facades\OpenAI\Enums\Model;
use App\Facades\OpenAIFacade;

class OpenAIService {
    public const MODEL = Model::ADA_002;
    public const MAX_TOKENS_COUNT = 8191;

    public function createEmbedding(string $content) {

        abort_if(
            OpenAIFacade::countTokens(self::MODEL, $content) > self::MAX_TOKENS_COUNT,
            400,
            __('The maximum number of tokens has been exceeded!')
        );

        if (!ElasticsearchFacade::isIndexExists()) {
            ElasticsearchFacade::createIndex();
        }

        abort_if(
            !ElasticsearchFacade::isIndexExists(),
            400,
            'Failed to create an index!'
        );

        $embeddingContent = OpenAIFacade::createEmbedding(self::MODEL, $content)->getEmbedding();

        ElasticsearchFacade::createDocument($embeddingContent, $content);
        return $embeddingContent;
    }

    function seedEmbeddings() {

        $data = $this->parseDataset(base_path() . '/sts-test.csv');

        foreach ($data as $row) {
            $this->createEmbedding($row['description1']);
            sleep(21);
        }

        return true;
    }

    public function parseDataset(string $csvFilePath)
    {
        $valuesArray = array();

        if (($handle = fopen($csvFilePath, 'r'))) {

            while (($data = fgetcsv($handle, 1000, "\t"))) {

                $rowArray = array(
                    'column1' => $data[0],
                    'column2' => $data[1],
                    'column3' => $data[2],
                    'column4' => $data[3],
                    'column5' => $data[4],
                    'description1' => $data[5],
                    'description2' => $data[6],
                );

                $valuesArray[] = $rowArray;
            }

            fclose($handle);
        }

        return $valuesArray;
    }

    public function saveEmbeddingsToCsv() {
        $data = $this->parseDataset(base_path() . '/sts-test-100.csv');

        $fileHandle = fopen(base_path() . '/descriptions.csv', 'w');

        foreach ($data as $row) {
            $embeddingContent = OpenAIFacade::createEmbedding(self::MODEL, $row['description2'])->getEmbedding();
            $embeddingSerialized = serialize($embeddingContent);
            fputcsv($fileHandle, [$row['description2'], $embeddingSerialized]);
            sleep(21);
        }

        fclose($fileHandle);

        return true;
    }

    public function saveScoresToCsv() {
        $i = 1;

        if (($handle = fopen(base_path() . '/descriptions.csv', 'r'))) {

            while (($data = fgetcsv($handle, 100000, ","))) {

//                $fileHandle = fopen(base_path() . "/results-$i.csv", 'w');
                $fileHandle = fopen(base_path() . "/items.csv", 'a');

//                $recommendations = ElasticsearchFacade::getRecommendations(unserialize($data[1]), 100);
                $recommendations = ElasticsearchFacade::getScore(unserialize($data[1]), $i);

                foreach ($recommendations as $recommendation) {
                    fputcsv($fileHandle, [$recommendation['_id'], $data[0], $recommendation['_source']['description'], $recommendation['_score']]);
                }

                fclose($fileHandle);

                $i += 1;
            }

            fclose($handle);
        }

        return true;
    }
}
