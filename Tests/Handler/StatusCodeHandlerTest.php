<?php

namespace Stadline\StatusPageBundle\Tests\Handler;

use Mockery as m;
use Stadline\StatusPageBundle\Requirements\AppRequirement;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StatusCodeHandlerTest extends WebTestCase
{
    protected $statusCodeHandlerMock;
    protected $requirementCollectionsMock;

    public function setUp()
    {
        $this->initMock();
    }

    public function tearDown()
    {
        m::close();
    }

    protected function initMock()
    {
        $this->statusCodeHandlerMock = m::mock("Stadline\StatusPageBundle\Handler\StatusCodeHandler")->shouldAllowMockingProtectedMethods()->makePartial();
        $this->requirementCollectionsMock = m::mock("Stadline\StatusPageBundle\Requirements\RequirementCollections");
    }

    public function testDefineMostCriticalStatusCodeWithSymfonyRequirementsFailedReturns500()
    {
        $collectionName = "Symfony";
        $failedRequirementsCollection = $this->getFailedRequirementsCollectionWithSymfonyFailed();

        $this->statusCodeHandlerMock->shouldReceive('getMostCriticalStatusCode')
            ->with($collectionName, $failedRequirementsCollection[$collectionName])
            ->andReturn(500);
        $this->statusCodeHandlerMock->shouldReceive('defineMostCriticalStatusCode')
            ->passthru();

        $statusCodeReturned = $this->statusCodeHandlerMock->defineMostCriticalStatusCode($failedRequirementsCollection);

        $this->assertEquals(500, $statusCodeReturned);
    }

    public function testDefineMostCriticalStatusCodeWithVersionRequirementsFailedReturns417()
    {
        $collectionName = "Version";
        $failedRequirementsCollection = $this->getFailedRequirementsCollectionWithVersionFailed();

        $this->statusCodeHandlerMock->shouldReceive('getMostCriticalStatusCode')
            ->with($collectionName, $failedRequirementsCollection[$collectionName])
            ->andReturn(417);
        $this->statusCodeHandlerMock->shouldReceive('defineMostCriticalStatusCode')
            ->passthru();

        $statusCodeReturned = $this->statusCodeHandlerMock->defineMostCriticalStatusCode($failedRequirementsCollection);

        $this->assertEquals(417, $statusCodeReturned);
    }

    public function testDefineMostCriticalStatusCodeWithSymfonyAndVersionRequirementsFailedReturns500()
    {
        $collectionNameSymfony = "Symfony";
        $collectionNameVersion = "Version";
        $failedRequirementsCollection = $this->getFailedRequirementsCollectionWithSymfonyAndVersionFailed();

        $this->statusCodeHandlerMock->shouldReceive('getMostCriticalStatusCode')
            ->with('Symfony', $failedRequirementsCollection[$collectionNameSymfony])
            ->andReturn(500);
        $this->statusCodeHandlerMock->shouldReceive('getMostCriticalStatusCode')
            ->with('Version', $failedRequirementsCollection[$collectionNameVersion])
            ->andReturn(417);
        $this->statusCodeHandlerMock->shouldReceive('defineMostCriticalStatusCode')
            ->passthru();

        $statusCodeReturned = $this->statusCodeHandlerMock->defineMostCriticalStatusCode($failedRequirementsCollection);

        $this->assertEquals(500, $statusCodeReturned);
    }

    public function testGetRequirementsCollectionsWithIgnoreWarning()
    {
        $ignoreWarning = 1;

        $failedRequirementsCollectionsExpected = $this->getFailedRequirementsCollectionWithSymfonyAndVersionFailed();

        $this->requirementCollectionsMock->shouldReceive('getFailedRequirements')
            ->andReturn($failedRequirementsCollectionsExpected);

        $this->statusCodeHandlerMock->shouldReceive('getRequirementsCollections')
            ->passthru();

        $collectionsReturned = $this->statusCodeHandlerMock->getRequirementsCollections($this->requirementCollectionsMock, $ignoreWarning);

        $this->assertEquals($failedRequirementsCollectionsExpected, $collectionsReturned);
    }

    public function testGetRequirementsCollectionsWithoutIgnoreWarning()
    {
        $ignoreWarning = 0;

        $failedRequirementsCollection = $this->getFailedRequirementsCollectionWithSymfonyAndVersionFailed();
        $failedRecommendationsCollection = $this->getFailedRecommendationsCollectionWithSymfonyFailed();

        $this->requirementCollectionsMock->shouldReceive('getFailedRequirements')
            ->andReturn($failedRequirementsCollection);

        $this->requirementCollectionsMock->shouldReceive('getFailedRecommendations')
            ->andReturn($failedRecommendationsCollection);

        $this->statusCodeHandlerMock->shouldReceive('getRequirementsCollections')
            ->passthru();

        $collectionsExpected = array_merge_recursive($failedRequirementsCollection, $failedRecommendationsCollection);
        $collectionsReturned = $this->statusCodeHandlerMock->getRequirementsCollections($this->requirementCollectionsMock, $ignoreWarning);

        $this->assertEquals($collectionsExpected, $collectionsReturned);
        $this->assertCount(2, $collectionsExpected['Symfony']); // assert array merged recursively
    }

    public function testGetMostCriticalStatusCodeWithSymfonyRequirementsFailedReturns500()
    {
        $collectionName = "Symfony";
        $failedRequirements = $this->getFailedRequirementsCollectionWithSymfonyFailed()[$collectionName];

        $this->statusCodeHandlerMock->shouldReceive('getMostCriticalStatusCode')
            ->passthru();

        $statusCodeReturned = $this->statusCodeHandlerMock->getMostCriticalStatusCode($collectionName, $failedRequirements);

        $this->assertEquals(500, $statusCodeReturned);
    }

    public function testGetMostCriticalStatusCodeWithVersionRequirementsFailedReturns417()
    {
        $collectionName = "Version";
        $failedRequirements = $this->getFailedRequirementsCollectionWithVersionFailed()['Version'];

        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->with(0, [true, false, true])
            ->times(1)
            ->andReturn(417);
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->with(417, [true, false, true])
            ->times(2)
            ->andReturn(417);
        $this->statusCodeHandlerMock->shouldReceive('getMostCriticalStatusCode')
            ->passthru();

        $statusCodeReturned = $this->statusCodeHandlerMock->getMostCriticalStatusCode($collectionName, $failedRequirements);

        $this->assertEquals(417, $statusCodeReturned);
    }

    public function testGetStatusCodeReturns500WhenPreviouslyStatusCodeIs0Or409Or417()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // false (informative), false (dependant), true (fromApp) = 500
        $statusCodeReturnedWith0 = $this->statusCodeHandlerMock->getStatusCode(0, [false, false, true]);
        $statusCodeReturnedWith409 = $this->statusCodeHandlerMock->getStatusCode(409, [false, false, true]);
        $statusCodeReturnedWith417 = $this->statusCodeHandlerMock->getStatusCode(417, [false, false, true]);

        $this->assertEquals(500, $statusCodeReturnedWith0);
        $this->assertEquals(500, $statusCodeReturnedWith409);
        $this->assertEquals(500, $statusCodeReturnedWith417);
    }

    public function testGetStatusCodeReturns500WhenPreviouslyStatusCodeIs500()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // false (informative), false (dependant), true (fromApp) = 500
        $statusCodeReturned = $this->statusCodeHandlerMock->getStatusCode(500, [false, false, true]);

        $this->assertEquals(500, $statusCodeReturned);
    }

    public function testGetStatusCodeReturns409WhenPreviouslyStatusCodeIs0Or417()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // false (informative), true (dependant), false (fromApp) = 409
        $statusCodeReturnedWith0 = $this->statusCodeHandlerMock->getStatusCode(0, [false, true, false]);
        $statusCodeReturnedWith417 = $this->statusCodeHandlerMock->getStatusCode(417, [false, true, false]);

        $this->assertEquals(409, $statusCodeReturnedWith0);
        $this->assertEquals(409, $statusCodeReturnedWith417);
    }

    public function testGetStatusCodeReturns409WhenPreviouslyStatusCodeIs409()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // false (informative), true (dependant), false (fromApp) = 409
        $statusCodeReturned = $this->statusCodeHandlerMock->getStatusCode(409, [false, true, false]);

        $this->assertEquals(409, $statusCodeReturned);
    }

    public function testGetStatusCodeReturns500WhenPreviouslyStatusCodeIs500AndWillBeTo409Case()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // false (informative), true (dependant), false (fromApp) = 409
        $statusCodeReturned = $this->statusCodeHandlerMock->getStatusCode(500, [false, true, false]);

        $this->assertEquals(500, $statusCodeReturned);
    }

    public function testGetStatusCodeReturns417WhenPreviouslyStatusCodeIs0()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // true (informative), false (dependant), true (fromApp) = 417
        $statusCodeReturned = $this->statusCodeHandlerMock->getStatusCode(0, [true, false, true]);

        $this->assertEquals(417, $statusCodeReturned);
    }

    public function testGetStatusCodeReturns500WhenPreviouslyStatusCodeIs500AndWillBeTo417Case()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // true (informative), false (dependant), true (fromApp) = 417
        $statusCodeReturnedWith500 = $this->statusCodeHandlerMock->getStatusCode(500, [true, false, true]);

        $this->assertEquals(500, $statusCodeReturnedWith500);
    }

    public function testGetStatusCodeReturns409WhenPreviouslyStatusCodeIs409AndWillBeTo417Case()
    {
        $this->statusCodeHandlerMock->shouldReceive('getStatusCode')
            ->passthru();

        // true (informative), false (dependant), true (fromApp) = 417
        $statusCodeReturnedWith409 = $this->statusCodeHandlerMock->getStatusCode(409, [true, false, true]);

        $this->assertEquals(409, $statusCodeReturnedWith409);
    }

    private function getFailedRecommendationsCollectionWithSymfonyFailed()
    {
        $failedRequirementsCollection = [
            "Symfony" => [
                0 => new \Requirement(
                    false,
                    "Recommandation XXX",
                    "Need to fix this recommandation",
                    true
                )
            ]
        ];

        return $failedRequirementsCollection;
    }

    private function getFailedRequirementsCollectionWithSymfonyFailed()
    {
        $failedRequirementsCollection = [
            "Symfony" => [
                0 => new \Requirement(
                    false,
                    "Requirements file should be up-to-date",
                    "Your requirements file is outdated. Run composer install and re-check your configuration.",
                    false
                )
            ]
        ];

        return $failedRequirementsCollection;
    }

    private function getFailedRequirementsCollectionWithVersionFailed()
    {
        $failedRequirementsCollection = [
            "Version" => [
                0 => new AppRequirement(
                    false,
                    "Git commit tag",
                    "NONE",
                    false,
                    false,
                    true
                ),
                1 => new AppRequirement(
                    false,
                    "Git commit hash",
                    "NONE",
                    false,
                    false,
                    true
                ),
                2 => new AppRequirement(
                    false,
                    "Git branch",
                    "NONE",
                    false,
                    false,
                    true
                )
            ]
        ];

        return $failedRequirementsCollection;
    }

    private function getFailedRequirementsCollectionWithSymfonyAndVersionFailed()
    {
        $failedRequirementsCollection = array_merge(
            $this->getFailedRequirementsCollectionWithSymfonyFailed(),
            $this->getFailedRequirementsCollectionWithVersionFailed()
        );

        return $failedRequirementsCollection;
    }
}