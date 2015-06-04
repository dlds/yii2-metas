<?php

namespace dlds\metas;

use yii\web\View;

/**
 * OpenGraph meta data
 * @see http://ogp.me
 */
class OpenGraph {

    /**
     * @var string The title of your object as it should appear within the graph, e.g., "The Rock"
     */
    public $title;

    /**
     * @var string  The type of your object, e.g., "video.movie". Depending on the type you specify, other properties may also be required.
     */
    public $type;

    /**
     * @var string An image URL which should represent your object within the graph
     */
    public $image;

    /**
     * @var string The canonical URL of your object that will be used as its permanent ID in the graph, e.g., "http://www.imdb.com/title/tt0117500/".
     */
    public $url;

    /**
     * @var string A URL to an audio file to accompany this object.
     */
    public $audio;

    /**
     * @var string  A one to two sentence description of your object.
     */
    public $description;

    /**
     * @var string The word that appears before this object's title in a sentence. An enum of (a, an, the, "", auto). If auto is chosen, the consumer of your data should chose between "a" or "an". Default is "" (blank).
     */
    public $determiner;

    /**
     * @var string The locale these tags are marked up in. Of the format language_TERRITORY. Default is en_US.
     */
    public $locale;

    /**
     * @var array An array of other locales this page is available in.
     */
    public $locale_alternate;

    /**
     * @var string f your object is part of a larger web site, the name which should be displayed for the overall site. e.g., "IMDb".
     */
    public $site_name;

    /**
     * @var string A URL to a video file that complements this object.
     */
    public $video;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        \Yii::$app->view->on(View::EVENT_BEGIN_PAGE, function() {
            return $this->handlePageBegin();
        });
    }

    /**
     * Handles page begin event
     */
    protected function handlePageBegin()
    {
        \Yii::$app->controller->view->registerMetaTag(['property' => 'og:title', 'content' => $this->title], 'og:title');
        \Yii::$app->controller->view->registerMetaTag(['property' => 'og:site_name', 'content' => $this->site_name], 'og:site_name');
        \Yii::$app->controller->view->registerMetaTag(['property' => 'og:url', 'content' => $this->url], 'og:url');
        \Yii::$app->controller->view->registerMetaTag(['property' => 'og:type', 'content' => $this->type], 'og:type');

        // Locale issafe to be specifued since it has default value on Yii applications
        \Yii::$app->controller->view->registerMetaTag(['property' => 'og:locale', 'content' => $this->locale], 'og:locale');

        // Only add a description meta if specified
        if ($this->description !== null)
        {
            \Yii::$app->controller->view->registerMetaTag(['property' => 'og:description', 'content' => $this->description], 'og:description');
        }

        // Only add an image meta if specified
        if ($this->image !== null)
        {
            \Yii::$app->controller->view->registerMetaTag(['property' => 'og:image', 'content' => $this->image], 'og:image');
        }
    }
}