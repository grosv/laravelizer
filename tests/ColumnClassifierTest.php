<?php

namespace Tests;

use ColumnClassifier\Classifier;

class ColumnClassifierTest extends TestCase
{
    private $classifier;

    public function setUp(): void
    {
        parent::setUp();
        $this->classifier = new Classifier(collect(['Edward', 'Justine', 'Milo']));
    }

    /** @group always */
    public function testClassifier()
    {
        $this->assertEquals('first_name', $this->classifier->execute());
    }
}
