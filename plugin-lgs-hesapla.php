<?php
/*
Plugin Name: LGS Hesapla
Description: LGS Puan hesaplama formu
Version: 1.0.7
Author: Yunus AK
License: GPLv2 or later
*/

class DersVerisi
{
    public string $adi;
    public string $formNameDogru;
    public string $formNameYanlis;
    public int $soruSayisi;
    public int $dogruSayisi = 0;
    public int $yanlisSayisi = 0;
    public float $agirlikKatsayisi;
    public float $ortHamPuan;
    public float $ortStandartSapma;

    public function __construct(
        $adi,
        $formNameDogru,
        $formNameYanlis,
        $soruSayisi,
        $agirlikKatsayisi,
        $ortHamPuan,
        $ortStandartSapma
    ) {
        $this->adi = $adi;
        $this->formNameDogru = $formNameDogru;
        $this->formNameYanlis = $formNameYanlis;
        $this->soruSayisi = $soruSayisi;
        $this->agirlikKatsayisi = $agirlikKatsayisi;
        $this->ortHamPuan = $ortHamPuan;
        $this->ortStandartSapma = $ortStandartSapma;
    }

    public function setDogruSayisi(int $value)
    {
        $this->dogruSayisi = $value;
    }

    public function setYanlisSayisi(int $value)
    {
        $this->yanlisSayisi = $value;
    }

    public function hamPuan(): float
    {
        return $this->dogruSayisi - ($this->yanlisSayisi / 3);
    }

    public function standartPuan(): float
    {
        return 10 * (($this->hamPuan() - $this->ortHamPuan) / $this->ortStandartSapma) + 50;
    }

    public function agirlikliStandartPuan(): float
    {
        return $this->agirlikKatsayisi * $this->standartPuan();
    }

    public function sonPuan(): float
    {
        return $this->agirlikliStandartPuan() / $this->ortStandartSapma;
    }
}
function getFormValue($var = null)
{
    $var = array_key_exists($var, $_POST) ? intval($_POST[$var]) : 0;
    return $var;
}
function getFormValues($dersler)
{
    $errors = '';

    foreach ($dersler as $key => $ders) {
        $dersler[$key]->setDogruSayisi(getFormValue($ders->formNameDogru));
        $dersler[$key]->setYanlisSayisi(getFormValue($ders->formNameYanlis));
        if (
            ($dersler[$key]->dogruSayisi > $ders->soruSayisi) ||
            ($dersler[$key]->dogruSayisi < 0) ||
            ($dersler[$key]->dogruSayisi + $dersler[$key]->yanlisSayisi > $ders->soruSayisi)
        ) {
            $errors .= $dersler[$key]->adi . " dersinin cevap sayılarını kontrol edin!<br>\n";
        }
    }
    return $errors;
}

function isFormSubmitted()
{
    return isset($_POST['hsp_hesapla']);
}

function toplamPuan($dersVerileri, $minTASP, $maxTASP): float
{
    $toplamSonPuan = 0;

    foreach ($dersVerileri as $key => $ders) {
        $toplamSonPuan += $ders->sonPuan();
    }
    $toplamPuan = (($toplamSonPuan - $minTASP) * 400) / ($maxTASP - $minTASP) + 100;
    return $toplamPuan;
}

function lgs_hesapla_tablo_ciz($content)
{
    $keyword = '[LGS_HESAPLA]';
    $output = '';
    // Only do this when a single post is displayed
    if (is_single()  && str_contains($content, $keyword)) {
        $isError = false;
        $sonuc = '';
        $minTASP = 68;
        $maxTASP = 308;



        $tur = new DersVerisi('Türkçe',  'turd', 'tury', 20, 4, 9.41, 4.79);
        $ink = new DersVerisi('İnkılap', 'inkd', 'inky', 10, 1, 5.23, 2.87);
        $din = new DersVerisi('Din Kültürü', 'dind', 'diny', 10, 1, 6.35, 2.59);
        $dil = new DersVerisi('Yabancı Dil', 'dild', 'dily', 10, 1, 4.93, 3.34);
        $mat = new DersVerisi('Matematik',    'matd', 'maty', 20, 4, 4.2,  3.31);
        $fen = new DersVerisi('Fen Bilimleri', 'fend', 'feny', 20, 4, 8.04, 4.82);
        $dersler = [
            $tur,
            $ink,
            $din,
            $dil,
            $mat,
            $fen
        ];



        try {
            if (isFormSubmitted()) {
                $errors = getFormValues($dersler);
                if ($errors != '') throw new Exception($errors);
            } else {
                $sonuc = 'Değerleri girin ve hesapla butonuna basın';
            }
        } catch (\Throwable $th) {
            $isError = true;
            $errorMessage = $th->getMessage();
        }

        if (!$isError && isFormSubmitted()) {
            $output .= '
                <h4>Sonuçlar</h4>';

            $output .= '<div class="row table-header">';
            $output .=
                '<div class="column">'
                . 'Ders'
                . '</div>'
                . '<div class="column">'
                . 'Doğru/Yanlış Sayısı'
                . '</div>'
                . '<div class="column">'
                . 'Ham Puan'
                . '</div>';
            $output .= "</div>";
            foreach ($dersler as $ders) {
                $output .= '<div class="row">';
                $output .=
                    '<div class="column">'
                    . $ders->adi
                    . '</div>'
                    . '<div class="column">'
                    . number_format($ders->dogruSayisi) . '/' . number_format($ders->yanlisSayisi)
                    . '</div>'
                    . '<div class="column">'
                    . number_format($ders->hamPuan(), 2)
                    . '</div>';
                $output .= "</div>";
            }
            $output .= '<div class="row"><h3><p><b>Toplam LGS Puanınız: '
                . number_format(toplamPuan($dersler, $minTASP, $maxTASP), 4)
                . '</b></p></h3></div>';
            $output .= '
            <div class="row">
                    <div class="column">
                        <a href="">Tekrar Hesapla</a>
                    </div>
                </div>
            ';
        } else {
            $output = '   
            <form method="post" action="#" name="lgs_form" id="lgs_form">
                <div class="row">
                    <div class="column table-header">Ders</div>
                    <div class="column table-header">Doğru Sayısı</div>
                    <div class="column table-header">Yanlış Sayısı</div>
                </div>';
            foreach ($dersler as $ders) {
                $output .= '
                    <div class="row">
                        <div class="column">
                            <b>' . $ders->adi . '(' . $ders->soruSayisi . ')</b>
                        </div>
                        <div class="column">
                            <input name="' . $ders->formNameDogru . '" type="number" class="form-control"  placeholder="0"  value="'
                    . ($ders->dogruSayisi ?: '')
                    . '" max="' . $ders->soruSayisi . '" min="0">
                        </div>
                        <div class="column">
                            <input name="' . $ders->formNameYanlis
                    . '" type="number" class="form-control" placeholder="0" value="'
                    . ($ders->yanlisSayisi ?: '') .
                    '" max="' . $ders->soruSayisi . '" min="0">
                        </div>
                    </div>';
            }

            $output .= '       
                <div class="row">
                    <div class="column">
                        <input class="lgs-submit-button" aria-label="hesapla" type="submit" name="hsp_hesapla" value="HESAPLA">
                    </div>
                </div>
            </form>';

            if ($isError) {
                $output .= '<h3>Hata</h3>
                    <p>' . $errorMessage . '</p>';
            } else {
                $output .= '<div class="row"><div class="column">Doğru ve yanlış cevap sayılarını girip hesapla butonuna basın.</div></div>';
            }
        }


        return str_replace('[LGS_HESAPLA]', $output, $content);
    }
}

function enqueue_related_pages_scripts_and_styles()
{
    wp_enqueue_style('related-styles', plugins_url('/style.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'enqueue_related_pages_scripts_and_styles');
add_filter('the_content', 'lgs_hesapla_tablo_ciz');
