<?php

declare(strict_types=1);

namespace M2E\Test\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Serialize\SerializerInterface;
use M2E\Test\Model\TestRepository;
use Magento\Framework\File\Csv;

class ImportDataCommand extends Command
{
    private State $state;
    private File $fileSystem;
    private SerializerInterface $serializer;
    private TestRepository $testRepository;
    private Csv $csv;

    /**
     * @param State $state
     * @param File $fileSystem
     * @param SerializerInterface $serializer
     * @param TestRepository $testRepository
     * @param Csv $csv
     */
    public function __construct(
        State $state,
        File $fileSystem,
        SerializerInterface $serializer,
        TestRepository $testRepository,
        Csv $csv
    ) {
        parent::__construct();
        $this->state = $state;
        $this->fileSystem = $fileSystem;
        $this->serializer = $serializer;
        $this->testRepository = $testRepository;
        $this->csv = $csv;
    }

    protected function configure()
    {
        $this->setName('m2e:import');
        $this->setDescription('Import data from XML or CSV file to custom table');
        $this->addArgument(
            'file_path',
            InputArgument::REQUIRED,
            'Path to the XML or CSV file'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode('adminhtml');
        $filePath = $input->getArgument('file_path');

        if ($this->fileSystem->fileExists($filePath)) {
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            switch (strtolower($extension)) {
                case 'xml':

                    $xmlData = $this->fileSystem->read($filePath);
                    $dataArray = $this->parseXmlToArray($xmlData);

                    if (!$dataArray) {
                        $output->writeln('<error>Failed to parse XML data.</error>');
                        return;
                    }

                    $nestedData = $dataArray;

                    $data = [];

                    // Initialize an array to hold the header row
                    $headerRow = [];

                    // Check if the Worksheet key exists in the nested data
                    if (isset($nestedData['Worksheet']['Table']['Row'])) {
                        foreach ($nestedData['Worksheet']['Table']['Row'] as $rowIndex => $row) {
                            // Check if 'Cell' key exists and if it's an array
                            if (isset($row['Cell']) && is_array($row['Cell'])) {
                                $rowData = [];
                                foreach ($row['Cell'] as $cellIndex => $cell) {
                                    // Extract the 'Data' field from the $cell array
                                    if (isset($cell['Data'])) {
                                        $columnValue = $cell['Data'];
                                        // Ensure that $columnValue is not an array (avoid nested arrays)
                                        if (!is_array($columnValue)) {
                                            if ($rowIndex === 0) {
                                                // Store the header row values
                                                $headerRow[$cellIndex] = $columnValue;
                                            } else {
                                                // Use the header row values as keys for data rows
                                                $rowData[$headerRow[$cellIndex]] = $columnValue;
                                            }
                                        }
                                    }
                                }

                                // Add the extracted row data to the flat data array (skip the first row)
                                if (!empty($rowData) && $rowIndex !== 0) {
                                    $data[] = $rowData;
                                }
                            }
                        }
                    }
                    break;
                case 'csv':
                    $csvData = $this->csv->getData($filePath);
                    $data = [];

                    if (!empty($csvData)) {
                        $header = array_shift($csvData);

                        foreach ($csvData as $row) {
                            $rowData = [];
                            foreach ($header as $index => $columnName) {
                                $rowData[trim($columnName)] = trim($row[$index]);
                            }
                            $data[] = $rowData;
                        }
                    }
                    break;
                default:
                    $output->writeln('<error>Unsupported file format.</error>');
                    return;
            }

            foreach ($data as $item) {
                $testModel = $this->testRepository->getTest();
                $testModel->setData($item);
                $this->testRepository->save($testModel);
            }

            $output->writeln('<info>Data imported successfully.</info>');
        } else {
            $output->writeln('<error>File not found.</error>');
        }
    }

    /**
     * @param $xmlData
     * @return mixed
     */
    private function parseXmlToArray($xmlData): mixed
    {
        $xml = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        return $array;
    }
}
