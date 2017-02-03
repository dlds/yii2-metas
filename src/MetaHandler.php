<?php

namespace dlds\metas;

use yii\web\View;
use dlds\metas\interfaces\MetaTaggerInterface;

/**
 * OpenGraph meta data
 * @see http://ogp.me
 */
class MetaHandler extends \yii\base\Component
{

    /**
     * Open graph Types
     */
    // activities
    const T_OG_ACTIVITY = 'activity';
    const T_OG_SPORT = 'sport';
    // bussiness
    const T_OG_BAR = 'bar';
    const T_OG_COMPANY = 'company';
    const T_OG_CAFE = 'cafe';
    const T_OG_HOTEL = 'hotel';
    const T_OG_RESTAURANT = 'restaurant';
    // groups
    const T_OG_CAUSE = 'cause';
    const T_OG_SPORTS_LEAGUE = 'sports_league';
    const T_OG_SPORTS_TEAM = 'sports_team';
    // organizations
    const T_OG_BAND = 'band';
    const T_OG_GOVERNMENT = 'government';
    const T_OG_NON_PROFIT = 'non_profit';
    const T_OG_SCHOOL = 'school';
    const T_OG_UNIVERSITY = 'university';
    // people
    const T_OG_ACTOR = 'actor';
    const T_OG_ATHLETE = 'athlete';
    const T_OG_AUTHOR = 'author';
    const T_OG_DIRECTOR = 'director';
    const T_OG_MUSICIAN = 'musician';
    const T_OG_POLITICIAN = 'politician';
    const T_OG_PROFILE = 'profile';
    const T_OG_PUBLIC_FIGURE = 'public_figure';
    // places
    const T_OG_CITY = 'city';
    const T_OG_COUNTRY = 'country';
    const T_OG_LANDMARK = 'landmark';
    const T_OG_STATE_PROVINCE = 'state_province';
    // products & entertainment
    const T_OG_ALBUM = 'album';
    const T_OG_BOOK = 'book';
    const T_OG_DRINK = 'drink';
    const T_OG_FOOD = 'food';
    const T_OG_GAME = 'game';
    const T_OG_MOVIE = 'movie';
    const T_OG_PRODUCT = 'product';
    const T_OG_SONG = 'song';
    const T_OG_TV_SHOW = 'tv_show';
    // websites
    const T_OG_ARTICLE = 'article';
    const T_OG_BLOG = 'blog';
    const T_OG_WEBSITE = 'website';

    /**
     * Details
     */
    const D_OG_PUBLISHER = 'publisher';
    const D_OG_PUBLISHED_OG_TIME = 'published_time';
    const D_OG_MODIFIED_OG_TIME = 'modified_time';
    const D_OG_EXPIRATION_TIME = 'expiration_time';
    const D_OG_AUTHOR = 'author';
    const D_OG_SECTION = 'section';
    const D_OG_TAG = 'tag';

    /**
     * FB
     */
    const FB_APP_ID = 'app_id';

    /**
     * Templates
     */
    const TMPL_OG = 'og:%s';
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
     * @var boolean indicates if ajax requests will be handled
     */
    public $handleAjax = false;

    /**
     * @var boolean
     */
    public $handleOg = true;

    /**
     * @var boolean|string
     */
    public $suffix = true;

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (null === $value) {
            return false;
        }

        parent::__set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->handleAjax && \Yii::$app->request->isAjax) {
            return false;
        }

        \Yii::$app->view->on(View::EVENT_BEGIN_PAGE, function() {
            return $this->handlePageBegin();
        });

        // default initialization goes here
        $this->site_name = \Yii::$app->name;
        $this->locale = \Yii::$app->language;
        
        if(true === $this->suffix) {
            $this->suffix = $this->site_name;
        }
    }

    /**
     * Registers meta
     * @param ModelOpenGraphInterface $model
     */
    public function register(MetaTaggerInterface $model, \Closure $callback)
    {
        $this->title = $model->__mtTitle();

        $this->audio = $model->__mtAudio();
        $this->description = $model->__mtDescription();
        $this->details = $model->__mtDetails();
        $this->determiner = $model->__mtDeterminer();
        $this->image = $model->__mtImage();
        $this->locale = $model->__mtLocale();
        $this->locale_alternate = $model->__mtLocale();
        $this->type = $model->__mtType();
        $this->url = $model->__mtUrl();
        $this->video = $model->__mtVideo();

        call_user_func($callback, $this, $model);
    }

    /**
     * Handles page begin event
     */
    protected function handlePageBegin()
    {
        if(!$this->title) {
            $this->title = $this->site_name;
        }
        
        if ($this->suffix) {
            
            $this->title = sprintf('%s | %s', $this->title, $this->suffix);
        }

        $this->_registerMetas();
        
        if ($this->handleOg) {
            $this->_registerOpenGraphs();
        }
    }

    /**
     * Registeres general metas
     */
    private function _registerMetas()
    {
        \Yii::$app->view->title = $this->title;

        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $this->description,
        ]);
    }

    /**
     * Registeres open graps metas
     */
    private function _registerOpenGraphs()
    {
        $properties = get_object_vars($this);

        // registeres all properties
        foreach ($properties as $key => $value) {
            if (in_array($key, ['locale_alternate', 'details', 'fb'])) {
                continue;
            }

            $this->_og($key, $value);
        }

        // registeres details tags
        if (is_array($this->details)) {
            $tmpl = sprintf('%s:%%s', $this->type);
            foreach ($this->details as $key => $value) {
                $this->_og($key, $value, $tmpl);
            }
        }

        // registeres alternate locales
        if (is_array($this->locale_alternate)) {
            foreach ($this->locale_alternate as $key => $value) {
                $this->_og($key, $value, self::TMPL_LOCALES);
            }
        }


        // registeres fb tags
        if (is_array($this->fb)) {
            foreach ($this->fb as $key => $value) {
                $this->_og($key, $value, self::TMPL_FB);
            }
        }
    }

    /**
     * Registeres open graph
     * @param string $key
     * @param string $value
     * @param string $tmpl
     */
    private function _og($key, $value, $tmpl = self::TMPL_OG)
    {
        if ($value) {
            $property = sprintf($tmpl, $key);

            \Yii::$app->view->registerMetaTag([
                'property' => $property,
                'content' => $value
                ], $property);
        }
    }

}
