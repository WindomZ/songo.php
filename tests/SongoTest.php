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
}
