<?php

    $apiKey = "a3af14b002655afb0616371f634ec6b9";
    $url = "http://api.viversum.de/xml/horoscopeDayFormal?apikey={$apiKey}";
    $res = file_get_contents($url);

    $xml = simplexml_load_string($res, null, LIBXML_NOCDATA);

    if( ! $xml->horoscope->zodiacSign) {
        return;
    }

    $count = 0;
    foreach($xml->horoscope->zodiacSign as $sign) {
        $from = date($sign->dateFrom);
        $to = date($sign->dateTo);
        $now = date('Y-m-d', time());
        if ($now <= $to && $now >= $from) {
            $currentSign = $xml->horoscope->zodiacSign[$count];
        }
        $count++;
    }

    if(isset($_GET['debug']) && $_GET['debug'] === 666) {
        print '<pre>'.print_r($currentSign, true).'</pre>';
    }

?>

    <link href="style.css" rel="stylesheet">
    <style>
        .hs-wrap {
            padding: 25px;
            margin: 1.75rem 0;
            border: 1px solid lightgrey;
            border-radius: 5px;
        }
        .hs-heading {
            padding: 3rem 0;
        }
        .hs-content-strong {
            margin: 0 0.25rem 1.5rem 0;
        }
        .hs-content-p {
            display: inline
        }
        @media (max-width: 768px) {
            .hs-heading {
                padding: 2rem 0;
            }
        }
        @media (max-width: 479px) {
            .hs-content-p {
                display: block;
            }
        }
    </style>

    <div class="main-wrapper">
        <div class="container">
            <div class="hs-wrap">
                <div class="row">
                    <div class="col-xs-12">
                        <p class="hs-date"><?php echo date("d. F Y"); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="hs-heading"><?php echo $currentSign->name; ?></h1>
                    </div>
                </div>
                <?php foreach($currentSign->section as $key => $values): ?>

                    <?php if(property_exists($values, "headline")): ?>
                      <div class="row">
                          <div class="col-xs-12">
                              <div>
                                  <strong class="hs-content-strong"><?php echo $values->headline; ?>:</strong>
                                  <p class="hs-content-p"><?php echo $values->content; ?></p>
                              </div>
                          </div>
                      </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
