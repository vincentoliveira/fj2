<?php

namespace FJ\MainBundle\Tests\Service;

use FJ\MainBundle\Tests\FJWebTestCase;

/**
 * Description of TwitterFeedServiceTest
 */
class TwitterFeedServiceTest extends FJWebTestCase
{

    /**
     * @var \FJ\MainBundle\Service\TwitterFeedService
     */
    protected $service;

    protected function setup()
    {
        parent::setup();
        $this->service = $this->container->get('fj.twitter_feed_service');
    }
    
    public function testUserTimelineUserDoestNotExist()
    {
        $tweets = $this->service->getUserTimeline('userthatdoesnotexist');
        $this->assertEmpty($tweets);
    }
    
    public function testUserTimeline()
    {
        $tweets = $this->service->getUserTimeline('twitter');
        $this->assertNotEmpty($tweets);
    }
    
}
