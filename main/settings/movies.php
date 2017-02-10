<?php
/**
 * URL reference
 *
 *  Movie Data API:         http://www.omdbapi.com/
 *  Subtitle Convert:       https://lab.sorz.org/tools/asstosrt/?lang=zh-tw
 *                          https://atelier.u-sub.net/srt2vtt/
 *  Subtitle time adject:   http://subshifter.bitsnbites.eu/
 *
 * Category Array Example
 *
 *   '{movies_sub_dir}' => array(
 *      'name' => '{category_name}',                    // (str)
 *      'poster' => '{poster_url}',                     // (str)
 *   ),
 *
 * Movie Array Example
 *
 *   array(
 *      {category} => array(                            // (str) default | movies_sub_dir
 *          'id' => '{imbd_id}',                        // (int) -optional-
 *          'name' => '{movie_name}',                   // (str)
 *          'year' => '{movie_release_year}'            // (int)
 *          'poster' => '{movie_poster_url}',           // (str) -optional-
 *          'filename' => '{movie_mp4_mkv_filename}',   // (str)
 *          'subtitle' => array(                        // (arr) -optional-
 *             '{lang}' => '{movie_vtt_filename}',      // key (str) en | zt | zs : value (str)
 *          )
 *      )
 *   ),
 *
 */
 
 $SETTING['CATEGORY'] = array(
    'tv_series' => array(
        'name' => '(TV series)',
        'poster' => 'http://a.b.com/image/poster.jpg',
    ),
);

$SETTING['MOVIE'] = array(
    'default' => array(
        array(
            'id' => 'tt123456',
            'name' => 'My Movie',
            'year' => '2017',
            'filename' => 'My.Movie.2017.720p.mp4',
        ),
        /* ... */
    ),
    'tv_series' => array(
        array(
            'name' => 'EP01 - TV001',
            'filename' => 'E01.mkv',
        ),
        /* ... */
    )
);
