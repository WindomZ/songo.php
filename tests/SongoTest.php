<?php declare(strict_types=1);

namespace Songo;

use PHPUnit\Framework\TestCase;

class SongoTest extends TestCase
{
    public function testNewSongo()
    {
        $songo = new Songo();
        self::assertNotEmpty($songo);

        return $songo;
    }

    /**
     * @covers  Parser::parseRawURL()
     * @depends testNewSongo
     * @param Songo $songo
     * @return array
     */
    public function testParseRawURL(Songo $songo)
    {
        $r = $songo->parseRawURL(
            'https://github.com/WindomZ/songo.php'.
            '?_limit=11&_page=22&&_sort=aaa&_sort=bbb'.
            '&name=xxx&age=28'
        );

        $this->assertEmpty($r['_analyze']);

        $this->assertEquals(11, $songo->getLimit());
        $this->assertEquals(22, $songo->getPage());
        $this->assertEquals('aaa', $songo->getSort()[0]);
        $this->assertEquals('bbb', $songo->getSort()[1]);

        $this->assertNotEmpty($r);
        $this->assertEquals(11, $r['_limit']);
        $this->assertEquals(22, $r['_page']);
        $this->assertEquals('aaa', $r['_sort'][0]);
        $this->assertEquals('bbb', $r['_sort'][1]);

        return array('songo' => $songo, 'result' => $r);
    }

    /**
     * @covers  Query::analyze()
     * @depends testParseRawURL
     * @param array $result
     * @return array
     */
    public function testAnalyze(array $result)
    {
        $this->assertEmpty($result['result']['_analyze']);

        $songo = $result['songo'];
        $this->assertNotEmpty($songo);

        $songo->setInclude('name');
        $this->assertEmpty($songo->analyze());

        $songo->setInclude('aaa');
        $this->assertNotEmpty($songo->analyze());

        return $result;
    }

    /**
     * @covers  Query::getValues()
     * @depends testNewSongo
     * @param Songo $songo
     * @return Songo
     */
    public function testGetValues(Songo $songo)
    {
        $songo->parseRawURL(
            'https://127.0.0.1/demo?_limit=50&_page=2'.
            '&_sort=created,money,-level'.
            '&year=$gt$2015&year=$lt$2017&month=$bt$8,11&date=$eq$1&day=$in$0,6'
        );

        $values = $songo->getValues('year');

        foreach ($values as $i => $v) {
            $q = $v->getQuery();

            $this->assertEquals($v->getOperator(), $q->getOperator());

            switch ($v->getOperator()) {
                case '$gt':
                    $this->assertEquals($q->getValue(), 2015);
                    break;
                case '$lt':
                    $this->assertEquals($q->getValue(), 2017);
                    break;
                default:
                    $this->fail('not exist operator: '.$v->getOperator());
                    break;
            }
        }

        return $songo;
    }

    /**
     * @covers  Query::getQueryValues()
     * @depends testGetValues
     * @param Songo $songo
     * @return Songo
     */
    public function testGetQueryValues(Songo $songo)
    {
        $values = $songo->getQueryValues('year');

        foreach ($values as $i => $v) {
            switch ($v->getOperatorIndex(0)) {
                case '$gt':
                    $this->assertEquals($v->getValue(), 2015);
                    break;
                case '$lt':
                    $this->assertEquals($v->getValue(), 2017);
                    break;
                default:
                    $this->fail('not exist operator: '.$v->getOperator());
                    break;
            }
        }

        return $songo;
    }
}
