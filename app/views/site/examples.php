<?php
/**
 * Created by PhpStorm.
 * User: amir
 * Date: 21/12/2018
 * Time: 16:34
 */


$this->title = 'Примеры';

?>



<div class="site-examples">

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h2>Примеры</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 сol-md-4 col-lg-4 text-center">
                <h4>Ermil, Радостно</h4>
                <p>М. Ю. Лермонтов - "Бородино"</p>
                <audio controls>
                    <source src="/site/file?path=<?= urlencode("/sounds/examples/Ermil_good.wav") ?>" type="audio/wav">
                </audio>
                <p><a data-pjax=0 href="/site/file?path=<?= urlencode("/sounds/examples/Ermil_good.wav") ?>">Скачать</a></p>
            </div>
            <div class="col-xs-12 col-sm-6 сol-md-4 col-lg-4 text-center">
                <h4>Ermil, Нейтрально</h4>
                <p>А. А. Блок - "Ночь, улица, фонарь, аптека..."</p>
                    <audio controls>
                        <source src="/site/file?path=<?= urlencode("/sounds/examples/Ermil-neutral.wav") ?>" type="audio/wav">
                    </audio>
                <p><a data-pjax=0 href="/site/file?path=<?= urlencode("/sounds/examples/Ermil-neutral.wav") ?>">Скачать</a></p>
            </div>

            <div class="col-xs-12 col-sm-6 сol-md-4 col-lg-4 text-center">
                <h4>Zahar, Нейтрально</h4>
                <p>А. С. Пушкин - "У лукоморья дуб зеленый"</p>
                <audio controls>
                    <source src="/site/file?path=<?= urlencode("/sounds/examples/Zahar_neutral.wav") ?>" type="audio/wav">
                </audio>
                <p><a data-pjax=0 href="/site/file?path=<?= urlencode("/sounds/examples/Zahar_neutral.wav") ?>">Скачать</a></p>
            </div>

            <div class="col-xs-12 col-sm-6 сol-md-4 col-lg-4 text-center">
                <h4>Oksana, Нейтрально</h4>
                <p>М. Ю. Лермонтов - "Парус"</p>
                <audio controls>
                    <source src="/site/file?path=<?= urlencode("/sounds/examples/Oksana_neutral.wav") ?>" type="audio/wav">
                </audio>
                <p><a data-pjax=0 href="/site/file?path=<?= urlencode("/sounds/examples/Oksana_neutral.wav") ?>">Скачать</a></p>
            </div>


            <div class="col-xs-12 col-sm-6 сol-md-4 col-lg-4 text-center">
                <h4>Omazh, Радостно</h4>
                <p>Отрывок из новостей</p>
                <audio controls>
                    <source src="/site/file?path=<?= urlencode("/sounds/examples/Omazh_good.wav") ?>" type="audio/wav">
                </audio>
                <p><a data-pjax=0 href="/site/file?path=<?= urlencode("/sounds/examples/Omazh_good.wav") ?>">Скачать</a></p>
            </div>

            <div class="col-xs-12 col-sm-6 сol-md-4 col-lg-4 text-center">
                <h4>Jane, Раздраженно</h4>
                <p>Скороговорка "Шишкосушильная фабрика"</p>
                <audio controls>
                    <source src="/site/file?path=<?= urlencode("/sounds/examples/Jane_evil.wav") ?>" type="audio/wav">
                </audio>
                <p><a data-pjax=0 href="/site/file?path=<?= urlencode("/sounds/examples/Jane_evil.wav") ?>">Скачать</a></p>
            </div>


        </div>



    </div>
</div>
