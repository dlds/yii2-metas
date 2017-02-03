<?php

namespace dlds\metas\interfaces;

interface MetaTaggerInterface
{

    /**
     * @var string The title of your object as it should appear within the graph, e.g., "The Rock"
     */
    public function __mtTitle();

    /**
     * @var string  The type of your object, e.g., "video.movie". Depending on the type you specify, other properties may also be required.
     */
    public function __mtType();

    /**
     * @var string An image URL which should represent your object within the graph
     */
    public function __mtImage();

    /**
     * @var string The canonical URL of your object that will be used as its permanent ID in the graph, e.g., "http://www.imdb.com/title/tt0117500/".
     */
    public function __mtUrl();

    /**
     * @var string A URL to an audio file to accompany this object.
     */
    public function __mtAudio();

    /**
     * @var string  A one to two sentence description of your object.
     */
    public function __mtDescription();

    /**
     * @var string The word that appears before this object's title in a sentence. An enum of (a, an, the, "", auto). If auto is chosen, the consumer of your data should chose between "a" or "an". Default is "" (blank).
     */
    public function __mtDeterminer();

    /**
     * @var string The locale these tags are marked up in. Of the format language_TERRITORY. Default is en_US.
     */
    public function __mtLocale();

    /**
     * @var array An array of other locales this page is available in.
     */
    public function __mtLocaleAlternate();

    /**
     * @var string A URL to a video file that complements this object.
     */
    public function __mtVideo();

    /**
     * @var array details
     */
    public function __mtDetails();
}
