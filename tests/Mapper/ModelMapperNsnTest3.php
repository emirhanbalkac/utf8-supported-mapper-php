<?php

/**
 * Created by PhpStorm.
 */

use Common\Util\Xml;

class ModelMapperNsnTest3 extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Mapper\XmlModelMapper
     */
    public $modelMapper;

    public function setUp()
    {
        $this->modelMapper = new \Mapper\XmlModelMapper();
        parent::setUp();
    }

    /**
     * @param $validModel
     * @param $xml
     * @throws \Mapper\ModelMapperException
     * @dataProvider dataProvider
     */
    public function testMap($validModel, $xml)
    {
        $model = new NsnVideo();

        $this->modelMapper->map($xml, $model);
        $this->assertEquals($validModel, $model);
    }

    /**
     * @param $model
     * @param $validXml
     * @dataProvider dataProvider
     */
    public function testUnMap($model, $validXml)
    {
        $xml = $this->modelMapper->unmap($model);
        $this->assertSame($validXml, $xml);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {

        $model = new NsnVideo();
        $model->title = 'Lorem ipsum dolor sit amet';
        $model->duration = 52;
        $model->max_quality = 3;
        $model->main_thumb_index = 2;

        $m  = $model->nested[] = new NsnVideoNested();
        $m->lang = 'en';
        $m->text = 'Lorem ipsum dolor sit amet';

        $m  = $model->nested[] = new NsnVideoNested();
        $m->lang = 'ru';
        $m->text = 'Lorem ipsum dolor sit amet';


        return [
            [$model, Xml::loadFromFile(__DIR__ . '/../testFiles/nsn.xml')]
        ];
    }

}