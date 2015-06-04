<?php

namespace dlds\metas;

use yii\web\View;

/**
 * OpenGraph meta data
 * @see http://ogp.me
 */
class OpenGraph extends \yii\base\Component {

    /**
     * Types
     */
    // activities
    const TYPE_ACTIVITY = 'activity';
    const TYPE_SPORT = 'sport';
    // bussiness
    const TYPE_BAR = 'bar';
    const TYPE_COMPANY = 'company';
    const TYPE_CAFE = 'cafe';
    const TYPE_HOTEL = 'hotel';
    const TYPE_RESTAURANT = 'restaurant';
    // groups
    const TYPE_CAUSE = 'cause';
    const TYPE_SPORTS_LEAGUE = 'sports_league';
    const TYPE_SPORTS_TEAM = 'sports_team';
    // organizations
    const TYPE_BAND = 'band';
    const TYPE_GOVERNMENT = 'government';
    const TYPE_NON_PROFIT = 'non_profit';
    const TYPE_SCHOOL = 'school';
    const TYPE_UNIVERSITY = 'university';
    // people
    const TYPE_ACTOR = 'actor';
    const TYPE_ATHLETE = 'athlete';
    const TYPE_AUTHOR = 'author';
    const TYPE_DIRECTOR = 'director';
    const TYPE_MUSICIAN = 'musician';
    const TYPE_POLITICIAN = 'politician';
    const TYPE_PROFILE = 'profile';
    const TYPE_PUBLIC_FIGURE = 'public_figure';
    // places
    const TYPE_CITY = 'city';
    const TYPE_COUNTRY = 'country';
    const TYPE_LANDMARK = 'landmark';
    const TYPE_STATE_PROVINCE = 'state_province';
    // products & entertainment
    const TYPE_ALBUM = 'album';
    const TYPE_BOOK = 'book';
    const TYPE_DRINK = 'drink';
    const TYPE_FOOD = 'food';
    const TYPE_GAME = 'game';
    const TYPE_MOVIE = 'movie';
    const TYPE_PRODUCT = 'product';
    const TYPE_SONG = 'song';
    const TYPE_TV_SHOW = 'tv_show';
    // websites
    const TYPE_ARTICLE = 'article';
    const TYPE_BLOG = 'blog';
    const TYPE_WEBSITE = 'website';

    /**
     * Details
     */
    const DETAILS_PUBLISHER = 'publisher';
    const DETAILS_PUBLISHED_TIME = 'published_time';
    const DETAILS_MODIFIED_TIME = 'modified_time';
    const DETAILS_EXPIRATION_TIME = 'expiration_time';
    const DETAILS_AUTHOR = 'author';
    const DETAILS_SECTION = 'section';
    const DETAILS_TAG = 'tag';

    /**
     * FB
     */
    const FB_APP_ID = 'app_id';

    /**
     * Templates
     */
    const TMPL_DEFAULT = 'og:%s';
    const TMPL_LOCALES = 'locale:%s';
    const TMPL_FB = 'fb:%s';

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
    public $locale_alternate = [];

    /**
     * @var string f your object is part of a larger web site, the name which should be displayed for the overall site. e.g., "IMDb".
     */
    public $site_name;

    /**
     * @var string A URL to a video file that complements this object.
     */
    public $video;

    /**
     * @var array details
     */
    public $details = [];

    /**
     * @var array fb
     */
    public $fb = [];

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->init();

        \Yii::$app->view->on(View::EVENT_BEGIN_PAGE, function() {
            return $this->handlePageBegin();
        });
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        // default initialization goes here
        $this->site_name = \Yii::$app->name;
        $this->locale = \Yii::$app->language;
    }

    /**
     * Handles page begin event
     */
    protected function handlePageBegin()
    {
        $properties = get_object_vars($this);

        // registeres all properties
        foreach ($properties as $key => $value)
        {
            if (in_array($key, ['locale_alternate', 'details', 'fb']))
            {
                break;
            }

            $this->registerMeta($key, $value);
        }

        // registeres details tags
        if (is_array($this->details))
        {
            foreach ($this->details as $key => $value)
            {
                $this->registerMeta($key, $value, $this->getDetailsTmpl());
            }
        }

        // registeres alternate locales
        if (is_array($this->locale_alternate))
        {
            foreach ($this->locale_alternate as $key => $value)
            {
                $this->registerMeta($key, $value, self::TMPL_LOCALES);
            }
        }


        // registeres fb tags
        if (is_array($this->fb))
        {
            foreach ($this->fb as $key => $value)
            {
                $this->registerMeta($key, $value, self::TMPL_FB);
            }
        }
    }

    /**
     * Registeres meta tag
     * @param string $key
     * @param string $value
     * @param string $tmpl
     */
    protected function registerMeta($key, $value, $tmpl = self::TMPL_DEFAULT)
    {
        if ($value)
        {
            $property = sprintf($tmpl, $key);

            \Yii::$app->controller->view->registerMetaTag([
                'property' => $property,
                'content' => $value
                ], $property);
        }
    }

    /**
     * Retrieves details template
     * @return string tmpl
     */
    private function getDetailsTmpl()
    {
        return sprintf('%s:%%s', $this->type);
    }
}