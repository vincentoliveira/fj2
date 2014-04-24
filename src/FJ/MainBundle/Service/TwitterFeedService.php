<?php

namespace FJ\MainBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
/**
 * Description of TwitterFeedService
 * 
 * @Service("fj.twitter_feed_service")
 */
class TwitterFeedService
{

    /**
     * Container
     * 
     * @Inject("service_container")
     * @var \Symfony\Component\DependencyInjection\Container
     */
    public $container;

    /**
     *
     * @var \TwitterOAuth
     */
    private $_twitterOAuth;

    /**
     * @return \TwitterOAuth
     */
    protected function getTwitterOAuth()
    {
        if ($this->_twitterOAuth === null) {
            $consumer_key = $this->container->getParameter('twitter_api.consumer_key');
            $consumer_secret = $this->container->getParameter('twitter_api.consumer_secret');
            $oauth_token = $this->container->getParameter('twitter_api.oauth_token');
            $oauth_token_secret = $this->container->getParameter('twitter_api.oauth_secret');
            $this->_twitterOAuth = new \TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
        }
        return $this->_twitterOAuth;
    }

    /**
     * Get $screenName user timeline
     * 
     * @param string $screenName
     * @return array List of tweets
     */
    public function getUserTimeline($screenName)
    {
        $count = $this->container->getParameter('twitter_api.count');
        $url = $this->container->getParameter('twitter_api.usertimeline_url');
        $http_proxy = $this->container->getParameter('http_proxy');

        $connection = $this->getTwitterOAuth();
        if ($http_proxy) {
            $connection->proxy = $http_proxy;
        }

        $parameters = array();
        $parameters['screen_name'] = $screenName;
        $parameters['count'] = $count;
        $parameters['contributor_details'] = false;
        $timelineArray = $connection->get($url, $parameters);

        if ($timelineArray === null || !is_array($timelineArray)) {
            return array();
        }

        $timeline = array();
        foreach ($timelineArray as $tweet) {
            $tweetData = array();
            $tweetData['text'] = $tweet->text;
            $tweetData['source'] = '@' . $tweet->user->screen_name;
            $tweetData['description'] = $this->getTitleFromTweet($tweet->text);

            $media = isset($tweet->entities->media) ? $tweet->entities->media : null;
            if (!empty($media)) {
                $tweetData['media_url'] = $media[0]->media_url;
            } else {
                $tweetData['media_url'] = null;
            }

            $timeline[] = $tweetData;
        }

        return $timeline;
    }

    /**
     * Remove url from tweet to get title
     * 
     * @param string $tweet
     * @return string
     */
    protected function getTitleFromTweet($tweet)
    {
        $pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
        $replacement = "";
        return preg_replace($pattern, $replacement, $tweet);
    }

}
