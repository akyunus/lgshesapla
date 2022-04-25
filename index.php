<?php
$isError = false;
$sonuc ='';

$dersler = [
    [
        "adi" => "Türkçe",
        "soruSayi" => 20,
        "dogru" => 0,
        "formNameDogru" => "turd",
        "yanlis" => 0,
        "formNameYanlis" => "tury",
        "hamPuan" => 0
    ],
    [
        "adi" => "Matematik",
        "soruSayi" => 20,
        "dogru" => 0,
        "formNameDogru" => "matd",
        "yanlis" => 0,
        "formNameYanlis" => "maty",
        "hamPuan" => 0
    ]
];

function getFormValue($var = null)
{

    $var = intval($_POST[$var]);
    return $var;
}
function getFormValues($dersler)  
{
    foreach ($dersler as $key => $ders) {
        $dersler[$key]['dogru'] = getFormValue($ders['formNameDogru']);
        $dersler[$key]['yanlis'] = getFormValue($ders['formNameYanlis']);
        if (
            ($ders['dogru'] > 20) ||
            ($ders['dogru'] < 0) ||
            ($ders['dogru'] + $ders['yanlis'] > 20)
        ) {
            throw new Exception($ders['adi'] + ' dersinin Soru sayılarını kontrol edin!', 1);
        }
    }
}
try {
    getFormValues($dersler);
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
<tr>
<td><b>Türkçe (20)</b></td>
<td><input name="turd" aria-label="turd" type="number" class="form-control" value="" max="20" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
<td><input name="tury" aria-label="tury" type="number" class="form-control" value="" max="20" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
</tr>
<tr>
<td><b>Matematik (20)</b></td>
<td><input name="matd" aria-label="matd" type="number" class="form-control" value="" max="20" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
<td><input name="maty" aria-label="maty" type="number" class="form-control" value="" max="20" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
</tr>
<tr>
<td><b>Fen Bilimleri (20)</b></td>
<td><input name="fend" aria-label="fend" type="number" class="form-control" value="" max="20" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
<td><input name="feny" aria-label="feny" type="number" class="form-control" value="" max="20" min="0" ></td>
</tr>
<tr>
<td><b>İnkılap Tarihi (10)</b></td>
<td><input name="inkd" aria-label="inkd" type="number" class="form-control" value="" max="10" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
<td><input name="inky" aria-label="inky" type="number" class="form-control" value="" max="10" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();" ></td>
</tr>
<tr>
<td><b>Din Kültürü (10)</b></td>
<td><input name="dind" aria-label="dind" type="number" class="form-control" value="" max="10" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
<td><input name="diny" aria-label="diny" type="number" class="form-control" value="" max="10" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
</tr>
<tr>
<td><b>İngilizce (10)</b></td>
<td><input name="dild" aria-label="dild" type="number" class="form-control" value="" max="10" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
<td><input name="dily" aria-label="dily" type="number" class="form-control" value="" max="10" min="0" onkeypress="if (!window.__cfRLUnblockHandlers) return false; return onlyIntNumbers();"></td>
</tr>
</tbody>
</table>
<div style="margin-bottom:15px;">
<input aria-label="hesapla" type="submit" name="hsp_hesapla" value="LGS PUANIMI HESAPLA" class="btn btn-success mr-2 text-center" style="width:65%;color:#FFFFFF;background-color:#1E623F">
<input aria-label="temizle" type="button" name="reset hsp_temizle" value="Temizle" class="btn btn-info" onclick="if (!window.__cfRLUnblockHandlers) return false; teogTemizle()" style="width:30%; background-color:#7A0000; float:right;color:#FFFFFF">
</div>
</form>
<?php if ($isError) { ?>
<h3>Hata</h3>
<p><?=$errorMessage?></p>
<?php } else if ($sonuc !='') { ?>
    <h3>Sonuç</h3>
<?php } ?>