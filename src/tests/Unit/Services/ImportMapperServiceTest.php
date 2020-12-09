<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use Mockery;
use League\Csv\Reader;
use App\Services\ImportMapperService;
use App\Models\Contact;
use Illuminate\Http\File;
use Mockery\Generator\StringManipulation\Pass\Pass;
use PhpParser\Node\Expr\AssignOp\Concat;

class ImportMapperServiceTest extends TestCase
{
    public $fakeRecords = [];

    protected function setUp(): void 
    {
        parent::setUp();
        $this->fakeFile = Mockery::mock(File::class);
        $this->service = new ImportMapperService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeMockReader() {
        $mockReader = Mockery::mock('alias:League\Csv\Reader');
        
        $mockReader->shouldReceive('createFromString')
            ->with('')
            ->andReturn($mockReader)
            ->once();
        $mockReader->shouldReceive('setHeaderOffset')
            ->with(0)
            ->once();
        $mockReader->shouldReceive('getHeader')
            ->andReturn([]);

        return $mockReader;
    }

    private function makeMockModel() {
        return Mockery::mock('alias:App\Models\Contact');
    }

    /**
     *
     * @return void
     */
    public function testNoParameter()
    {
        $this->expectErrorMessage('Too few arguments to function App\Services\ImportMapperService::importContactsFromCsv()');
        $this->service->importContactsFromCsv();
    }

    /**
     *
     * @return void
     */
    public function testNullParameter()
    {
        $this->expectErrorMessage('Call to a member function getContent() on null');
        $this->service->importContactsFromCsv(NULL);
    }

    /**
     *
     * @return void
     */
    public function testWithEmptyRecordFile()
    {
        $this->fakeFile
            ->shouldReceive('getContent')
            ->andReturn('')
            ->once();
        
        $mockReader = $this->makeMockReader();
        $mockReader->shouldReceive('getRecords')
            ->once()
            ->andReturn(new \ArrayObject([]));
        
        $result = $this->service->importContactsFromCsv($this->fakeFile);

        $this->assertEmpty($result);
    }

    /**
     *
     * @return void
     */
    public function testWithRecordAndNoCustomAttributes()
    {
        $this->fakeFile
            ->shouldReceive('getContent')
            ->andReturn('')
            ->once();

        $mockReader = $mockReader = $this->makeMockReader();
        $mockModel = 
        $sample = new \ArrayObject([
            ['team_id' => 1, 'name' => 'test', 'phone' => 123, 'email' => 'test@example.com', 'sticky_phone_number_id' => 1234]
        ]);

        $mockReader->shouldReceive('getRecords')
            ->once()
            ->andReturn($sample);
        
        $mockModel = $this->makeMockModel();
        $mockModel->shouldReceive('create')
            ->once()
            ->andReturn($mockModel);

        $mockModel->shouldReceive('toArray')
            ->once();
        
        $result = $this->service->importContactsFromCsv($this->fakeFile);
        $this->assertNotEmpty($result);
    }

    public function testWithRecordAndCustomAttributes()
    {
        $this->fakeFile
            ->shouldReceive('getContent')
            ->andReturn('')
            ->once();

        $mockReader = $mockReader = $this->makeMockReader();
        $mockModel = 
        $sample = new \ArrayObject([
            ['team_id' => 1, 'name' => 'test', 'phone' => 123, 'email' => 'test@example.com', 'sticky_phone_number_id' => 1234, 'custom_attr' => 'custom_value']
        ]);

        $mockReader->shouldReceive('getRecords')
            ->once()
            ->andReturn($sample);
        
        $mockModel = $this->makeMockModel();
        $mockModel->shouldReceive('create')
            ->once()
            ->andReturn($mockModel);

        $mockRel = Mockery::mock();
        $mockRel->shouldReceive('create')->once();
        
        $mockModel->shouldReceive('customAttrs')
            ->once()
            ->andReturn($mockRel);
        
        $mockModel->shouldReceive('fresh')
            ->once();
        $mockModel->shouldReceive('load')
            ->once();
        $mockModel->shouldReceive('toArray')
            ->once();
        
        $result = $this->service->importContactsFromCsv($this->fakeFile);
        $this->assertNotEmpty($result);
    }
}
