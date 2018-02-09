<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ShoutboxTest extends TestCase
{

    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * Index (/shoutbox)
     */
    public function testApiShoutboxEntryIndex() {

        $shoutbox = factory('App\Shoutbox')->create();

        $response = $this->get('/v1/shoutbox');

        $response->assertStatus(200);

        $response->assertSee($shoutbox->username);
        $response->assertSee($shoutbox->message);

    }

    /**
     * Single entry (/shoutbox/<id>)
     */
    public function testApiShoutboxEntryView() {

        $shoutbox = factory('App\Shoutbox')->create();

        $response = $this->get('/v1/shoutbox/'.$shoutbox->entryID);

        $response->assertStatus(200);

        $response->assertSee($shoutbox->username);
        $response->assertSee($shoutbox->message);

    }

    /**
     * since_time filter (/shoutbox/?since_time=<timestamp>)
     */
    public function testApiShoutboxEntryTimeFilter() {

        $shoutboxOld = factory('App\Shoutbox')->create(['time' => Carbon::now()->subHour(1)->timestamp]);
        $shoutbox = factory('App\Shoutbox')->create(['time' => Carbon::now()->addMinute(1)->timestamp]);



        $response = $this->get('/v1/shoutbox?since_time='.Carbon::now()->timestamp);

        $response->assertStatus(200);

        $response->assertSee($shoutbox->username);
        $response->assertSee($shoutbox->message);

        $response->assertDontSee($shoutboxOld->username);
        $response->assertDontSee($shoutboxOld->message);
    }

    /**
     * fromApi filter (/shoutbox/?fromApi=<0/1>)
     */
    public function testApiShoutboxEntrySourceFilter() {

        $shoutboxWbb = factory('App\Shoutbox')->create(['fromApi' => false]);
        $shoutboxApi = factory('App\Shoutbox')->create(['fromApi' => true]);

        $response = $this->get('/v1/shoutbox/?fromApi=0');



        $response->assertStatus(200);

        $response->assertSee($shoutboxWbb->username);
        $response->assertSee($shoutboxWbb->message);

        $response->assertDontSee($shoutboxApi->username);
        $response->assertDontSee($shoutboxApi->message);

        $response = $this->get('/v1/shoutbox/?fromApi=1');

        $response->assertStatus(200);

        $response->assertDontSee($shoutboxWbb->username);
        $response->assertDontSee($shoutboxWbb->message);

        $response->assertSee($shoutboxApi ->username);
        $response->assertSee($shoutboxApi->message);

    }

    /**
     * 404
     */
    public function testApiShoutboxEntryNotFound() {

        $response = $this->get('/v1/shoutbox/-1');
        $response->assertStatus(404);

    }

    /**
     * Post
     */
    public function testApiShoutboxEntryStore() {


        $data = [
          'username' => 'ApiUser',
          'message'  => 'Just testing'
        ];

        $response = $this->post('/v1/shoutbox', $data);

        $response->assertStatus(201);

        $response->assertSee('ApiUser');
        $response->assertSee('Just testing');
    }
}
