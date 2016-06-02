<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
    	Başlangıç Noktasını Seçiniz:
        <select name="baslangic" id="baslangic">
        	<option value="0">A</option>
        	<option value="1">B</option>
        	<option value="2">C</option>
        	<option value="3">D</option>
        	<option value="4">E</option>
        	<option value="5">F</option>
        	<option value="6">G</option>
        	<option value="7">H</option>
        	<option value="8">I</option>
        </select>
        <input type="submit" name="gonder" id="gonder" value="Bütün Yolları Göster">
    </form>
    <hr>
</body>
</html>
<?php
	// cevir fonksiyonu ile dosyadan alacağımız harfleri sayısal verilere dönüştürerek işlem yapacağız.
	function cevir($deger){
		switch ($deger){
		   case "A": return 0; break;
		   case "B": return 1; break;
		   case "C": return 2; break;
		   case "D": return 3; break;
		   case "E": return 4; break;
		   case "F": return 5; break;
		   case "G": return 6; break;
		   case "H": return 7; break;
		   case "I": return 8; break;
		   default: return "";
		}
	}
	
	// yazdir fonksiyonunda başlangıç ve bitiş değerlerini harf krşılığına çevirip ekrana yazdırıyoruz.
	function yazdir($deger){
		switch ($deger){
		   case 0: return "A"; break;
		   case 1: return "B"; break;
		   case 2: return "C"; break;
		   case 3: return "D"; break;
		   case 4: return "E"; break;
		   case 5: return "F"; break;
		   case 6: return "G"; break;
		   case 7: return "H"; break;
		   case 8: return "I"; break;
		   default: return "";
		}
	}
	
	// bu fonksiyonda matematiksel işlemlerimi bittikten sonra elimize gelecek olan sayısal rotamızı harflere çevirerek daha anlaşılır hale getireceğiz.
	function yoluYazdir($dizi){
		$veri ="";
		foreach($dizi as $s)
		{
			
			yazdir($s);
			$veri.=yazdir($s)."-";
		}
		return $veri;
	}

$dosyaismi="girdi.txt"; // verilerimizin bulunduğu dosya
$okunan=file($dosyaismi); // dosyanın içeriğini satır satır okuyoruz.
$veriler = array();	// veriler isimli boş bir dizi açıyoruz, verileri burada toplayacağız.
foreach($okunan as $sira => $satir)
{
	$satir = explode(",",$satir);	// her gelen satırı virgüle göre ayırıp dizi haline getiriyoruz.
	
	if($satir[2] != 0)	// komşu olmayanları işleme tabi tutmuyoruz.
	{
		$veriler[cevir($satir[0])][cevir($satir[1])]=$satir[2];	// matematiksel işlemler için gerekli olan dizi yapısını oluşturuyruz.
	}
}
	// çıkacak sonuçların hepsini birleştirip bir dosyaya yazdıracağımız için bütün çıktıları bir araya toplamamız gerekiyor.
	$toplam = "";
	
	// bir başlangıç noktası seçilip form post edildiği zaman...
	if(isset($_POST['gonder'])){
		// seçilen başlangıç noktası
		$baslangic = $_POST['baslangic'];
		
		// başlangıç noktası ne olursa olsun, bütün noktalara olan uzaklığını bulmak için döngü kullanıyoruz.
		for($i=0;$i<=8;$i++){
			if($baslangic == $i) continue;
			$bitis = $i;
			
			$yolUzunluk = array();
			$gecici = array();
			foreach(array_keys($veriler) as $val) $gecici[$val] = 999;
			$gecici[$baslangic] = 0;
			
			//hesaplama işlemleri
			while(!empty($gecici)){
				$min = array_search(min($gecici), $gecici);
				if($min == $bitis) break;
				foreach($veriler[$min] as $key=>$val) if(!empty($gecici[$key]) && $gecici[$min] + $val < $gecici[$key]) {
					$gecici[$key] = $gecici[$min] + $val;
					$yolUzunluk[$key] = array($min, $gecici[$key]);
				}
				unset($gecici[$min]);
			}
			
			// yolu listeliyoruz.
			$yol = array();
			$son = $bitis;
			while($son != $baslangic){
				$yol[] = $son;
				$son = $yolUzunluk[$son][0];
			}
			$yol[] = $baslangic;
			$yol = array_reverse($yol);
			
			$sonuc = "Başlangıç Noktası: ".yazdir($baslangic)."<br>Bitiş Noktası: ".yazdir($bitis)."<br>Yol Uzunluğu ".$yolUzunluk[$bitis][1]." Birim <br>Rota: ".yoluYazdir($yol)."<br><br><br>";
			
			// bütün sonuç değerlerini tek değişkene topluyoruz.
			$toplam.=$sonuc;
		}
		// ekrana sonucu yazdırdık.
		echo $toplam;
		
		$toplam = str_replace("<br>","\r\n",$toplam);	// br komutu txt dosyalarında okunmadığı için kaçış karakterleri ile değiştirdik.
		$dosya = fopen('sonuc.txt', 'w');	// yazdırma işlemi.
		fwrite($dosya, $toplam);
		fclose($dosya);	// yazdırma işleminden sonra dosyayı kapattık.
	}
?>
