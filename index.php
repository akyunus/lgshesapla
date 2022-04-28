<?php
$isError = false;
$sonuc = '';

$dersler = [
    [
        "adi" => "Türkçe",
        "soruSayisi" => 20,
        "dogru" => 0,
        "formNameDogru" => "turd",
        "yanlis" => 0,
        "formNameYanlis" => "tury",
        "hamPuan" => 0
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
    ]
];

function getFormValue($var = null)
{

    $var = intval($_POST[$var]);
    return $var;
}
function getFormValues()
{
    global $dersler;
    foreach ($dersler as $key => $ders) {
        $dersler[$key]['dogru'] = getFormValue($ders['formNameDogru']);
        $dersler[$key]['yanlis'] = getFormValue($ders['formNameYanlis']);
        if (
            ($dersler[$key]['dogru'] > $ders['soruSayisi']) ||
            ($dersler[$key]['dogru'] < 0) ||
            ($dersler[$key]['dogru'] + $dersler[$key]['yanlis'] > $ders['soruSayisi'])
        ) {
            throw new Exception($dersler[$key]['adi'] . ' dersinin Soru sayılarını kontrol edin!', 1);
        }
    }
}
function calcHamPuan(){
	global $dersler;
	foreach ($dersler as $i=> $ders){
		$dersler[$i]['hamPuan'] = $ders['dogru'] - ($ders['yanlis'] / 3);
	}
}
try {
    getFormValues();
    calcHamPuan();
} catch (\Throwable $th) {
    $isError = true;
    $errorMessage = $th->getMessage();
}

?>

<form method="post" action="#" name="lgs_form" id="lgs_form">
    <table>
        <thead>
            <tr style="color:#FAFAFA;background-color:#8A604F">
                <th>Ders</th>
                <th>Doğru</th>
                <th>Yanlış</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dersler as $ders) : ?>
                <tr>
                    <td><b><?= $ders['adi'] ?> (<?= $ders['soruSayisi'] ?>)</b></td>
                    <td><input name="<?= $ders['formNameDogru'] ?>" type="number" class="form-control" value="<?= $ders['dogru'] ?>" max="<?= $ders['soruSayisi'] ?>" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
                    <td><input name="<?= $ders['formNameYanlis'] ?>" type="number" class="form-control" value="<?= $ders['yanlis'] ?>" max="<?= $ders['soruSayisi'] ?>" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <div style="margin-bottom:15px;">
        <input aria-label="hesapla" type="submit" name="hsp_hesapla" value="LGS PUANIMI HESAPLA" class="btn btn-success mr-2 text-center" style="width:65%;color:#FFFFFF;background-color:#1E623F">
        <input aria-label="temizle" type="button" name="reset hsp_temizle" value="Temizle" class="btn btn-info" onclick="if (!window.__cfRLUnblockHandlers) return false; teogTemizle()" style="width:30%; background-color:#7A0000; float:right;color:#FFFFFF">
    </div>
</form>
<?php if ($isError) { ?>
    <h3>Hata</h3>
    <p><?= $errorMessage ?></p>
<?php } else if ($sonuc != '') { ?>
    <h3>Sonuç</h3>
<?php } ?>
