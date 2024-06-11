<link rel="stylesheet" href="res/style/hilfe-seite.css">
<?php include 'inc/navigation.php'; ?>

<!-- FAQ -->

<div class="container col-12 col-sm-12 col-md-6 col-xl-6 Box-1">

<h2 class="text-white text-center">Häufig gestellte Fragen</h2>

    <!-- Akkordeon für FAQ -->
    <div class="custom-accordion" id="accordion_1">

        <!-- Frage 1: Anreise und Abreise -->
        <div class="accordion-item rounded-3">
            <h2 class="mb-0" id="headingOne">
                <button class="btn btn-link collapsed text-dark" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Anreise und
                    Abreise</button>
            </h2>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordion_1">
                <div class="accordion-body">
                    Anreise:
                    Starten Sie Ihre Reise entspannt und gut vorbereitet. Wählen Sie bequeme Transportmittel und
                    planen Sie genügend Zeit ein.
                    <br>
                    Abreise:
                    Beenden Sie Ihre Reise stressfrei. Prüfen Sie Ihr Gepäck, hinterlassen Sie Ihren Aufenthaltsort
                    ordentlich und genießen Sie die letzten Momente vor der Rückreise.
                </div>
            </div>
        </div>

        <!-- Frage 2: Zimmerausstattung -->
        <div class="accordion-item rounded-3">
            <h2 class="mb-0" id="headingTwo">
                <button class="btn btn-link collapsed text-dark" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false"
                    aria-controls="collapseTwo">Zimmerausstattung</button>
            </h2>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion_1">
                <div class="accordion-body">
                    Tauchen Sie ein in Komfort und Moderne – unsere Zimmer sind mit zeitgemäßen Badeinrichtungen,
                    einem hochauflösenden Flachbildfernseher und kostenlosem WLAN ausgestattet.
                </div>
            </div>
        </div>

        <!-- Frage 3: Frühstück -->
        <div class="accordion-item rounded-3">
            <h2 class="mb-0" id="headingThree">
                <button class="btn btn-link collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                    aria-expanded="false" aria-controls="collapseThree">Frühstück</button>
            </h2>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordion_1">
                <div class="accordion-body">
                    Beginnen Sie Ihren Tag mit einem reichhaltigen Frühstücksbuffet, das täglich von 07:00 bis 10:00
                    Uhr serviert wird – eine kulinarische Reise, um Ihre Sinne zu wecken.
                </div>
            </div>
        </div>

        <!-- Frage 4: Parken -->
        <div class="accordion-item rounded-3">
            <h2 class="mb-0" id="headingFour">
                <button class="btn btn-link collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour"
                    aria-expanded="false" aria-controls="collapseFour">Parken</button>
            </h2>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#accordion_1">
                <div class="accordion-body">
                    Ihr Auto ist sicher: Kostenlose Parkplätze direkt am Hotel stehen Ihnen zur
                    Verfügung – bequem und sorgenfrei parken für einen sorgenfreien Aufenthalt.
                </div>
            </div>
        </div>
    </div>

</div>

<?php include 'inc/footer.php'; ?>
