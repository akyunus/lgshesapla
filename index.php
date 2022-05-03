<?php

declare(strict_types=1);
$isError = false;
$sonuc = '';
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
}

$turkce = new DersVerisi("Türkçe", 'turd', 'tury', 20, 4, 9.41, 4, 79);
$matematik = new DersVerisi("Matematik", 'matd', 'maty', 20, 4, 4.2, 3, 31);
$dersler = [
    $turkce,
    $matematik
    /*[
        "adi" => "Türkçe",
        "soruSayisi" => 20,
        "dogru" => 0,
        "formNameDogru" => "turd",
        "yanlis" => 0,
        "formNameYanlis" => "tury",
        "hamPuan" => 0,
        "agirlikKatsayisi" => 4
    ],
    [
        "adi" => "Matematik",
        "soruSayisi" => 20,
        "dogru" => 0,
        "formNameDogru" => "matd",
        "yanlis" => 0,
        "formNameYanlis" => "maty",
        "hamPuan" => 0
    ],
    [
        "adi" => "Fen Bilimleri",
        "soruSayisi" => 20,
        "dogru" => 0,
        "formNameDogru" => "fend",
        "yanlis" => 0,
        "formNameYanlis" => "feny",
        "hamPuan" => 0
    ],
    [
        "adi" => "İnkılap Tarihi",
        "soruSayisi" => 10,
        "dogru" => 0,
        "formNameDogru" => "inkd",
        "yanlis" => 0,
        "formNameYanlis" => "inky",
        "hamPuan" => 0
    ],
    [
        "adi" => "Din Kültürü",
        "soruSayisi" => 10,
        "dogru" => 0,
        "formNameDogru" => "dind",
        "yanlis" => 0,
        "formNameYanlis" => "diny",
        "hamPuan" => 0
    ],
    [
        "adi" => "Yanbanci Dil",
        "soruSayisi" => 10,
        "dogru" => 0,
        "formNameDogru" => "dild",
        "yanlis" => 0,
        "formNameYanlis" => "dily",
        "hamPuan" => 0
    ]*/
];

function getFormValue($var = null)
{
    $var = array_key_exists($var,$_POST) ? intval($_POST[$var]) : 0;
    return $var;
}
function getFormValues()
{
    $errors = '';
    global $dersler;
    foreach ($dersler as $key => $ders) {
        $dersler[$key]->setDogruSayisi(getFormValue($ders->formNameDogru));
        $dersler[$key]->setYanlisSayisi(getFormValue($ders->formNameYanlis));
        if (
            ($dersler[$key]->dogruSayisi > $ders->soruSayisi) ||
            ($dersler[$key]->dogruSayisi < 0) ||
            ($dersler[$key]->dogruSayisi + $dersler[$key]->yanlisSayisi > $ders->soruSayisi)
        ) {
            $errors .= $dersler[$key]->adi . " dersinin Soru sayılarını kontrol edin!<br>\n";
        }
    }
    return $errors;
}

function isFormSubmitted()
{
    return isset($_POST['submit_hesapla']);
}

try {
    if (isFormSubmitted()) {
        $errors = getFormValues();
    if ($errors != '') throw new Exception($errors);
    } else {
        $sonuc = 'Değerleri girin ve hesapla butonuna basın';
    }
} catch (\Throwable $th) {
    $isError = true;
    $errorMessage = $th->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 31%;
  padding: 10px;
  margin: 1%;
  
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
</head>
<body>
<form method="post" action="#" name="lgs_form" id="lgs_form">
<div class="row" >
  <div class="column" style="color:#FAFAFA;background-color:#8A604F">Ders</div>
  <div class="column" style="color:#FAFAFA;background-color:#8A604F">Doğru</div>
  <div class="column" style="color:#FAFAFA;background-color:#8A604F">Yanlış</div>
</div>
<?php foreach ($dersler as $ders) : ?>
    <div class="row" >
                    <div class="column">
                        <b><?= $ders->adi ?> (<?= $ders->soruSayisi ?>)</b>
                    </div>
                    <div class="column">
                        <input 
                            name="<?= $ders->formNameDogru ?>" 
                            type="number" 
                            class="form-control" 
                            value="<?= $ders->dogruSayisi ?>" 
                            max="<?= $ders->soruSayisi ?>" 
                            min="0">
                    </div>
                    <div class="column">
                        <input 
                            name="<?= $ders->formNameYanlis ?>" 
                            type="number" 
                            class="form-control" 
                            value="<?= $ders->yanlisSayisi ?>" 
                            max="<?= $ders->soruSayisi ?>" 
                            min="0">
                    </div>
    </div>
<?php endforeach; ?>


  
    <div class="row">
  <div class="column"></div>
  <div class="column"> <input aria-label="hesapla" type="submit" name="hsp_hesapla" value="LGS PUANIMI HESAPLA" class="btn btn-success mr-2 text-center" style="width:99%;color:#FFFFFF;background-color:#1E623F"></div>
  <div class="column"><input aria-label="temizle" type="button" name="reset hsp_temizle" value="Temizle" class="btn btn-info" onclick="if (!window.__cfRLUnblockHandlers) return false; teogTemizle()" style="width:99%; background-color:#7A0000; float:right;color:#FFFFFF"></div>
</div>
    <div style="margin-bottom:15px;">
       
        
    </div>
</form>
<?php if ($isError) { ?>
    <h3>Hata</h3>
    <p><?= $errorMessage ?></p>
<?php } else if ($sonuc != '') { ?>
    <h3>Sonuçlar</h3>
<?php } ?>
